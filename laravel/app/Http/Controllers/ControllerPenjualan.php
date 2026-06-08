<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\ProdukKemasanModel;
use App\Models\PenjualanModel;
use App\Models\MemberModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControllerPenjualan extends Controller
{
    // 1. Menampilkan Halaman Utama Kasir
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['subtotal'];
        }

        // KEMASAN DIKOSONGKAN: Karena nanti akan diisi otomatis lewat JavaScript setelah barcode discan
        $daftarKemasan = []; 

        return view('kasir.transaksi', compact('cart', 'total', 'daftarKemasan'));
    }

    // TAMBAHKAN METHOD BARU INI: Untuk mendeteksi produk & mengambil kemasannya saja
    public function cariProdukKasir(Request $request)
    {
        $search = $request->search;

        if (!$search) {
            return response()->json(['success' => false, 'message' => 'Query kosong']);
        }

        // Cari produk berdasarkan barcode PAS atau Nama Produk yang Mirip
        $produk = ProdukModel::where('barcode', $search)
            ->orWhere('nama', 'LIKE', '%' . $search . '%')
            ->first();

        if ($produk) {
            // Jika produk ketemu, ambil hanya kemasan yang berhubungan dengan produk ini saja
            $kemasan = DB::table('produk_kemasans')
                ->where('produk_id', $produk->id)
                ->get();

            return response()->json([
                'success' => true,
                'produk'  => $produk,
                'kemasan' => $kemasan
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }

    // 2. Memasukkan Produk Ke Keranjang (Session)
    public function scan(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'kemasan_id' => 'required|exists:produk_kemasans,id',
            'qty' => 'required|integer|min:1',
        ]);

        $kemasan = ProdukKemasanModel::with('produk')->findOrFail($request->kemasan_id);
        $produk = $kemasan->produk;
        $qty = $request->qty;

        // Validasi kecocokan input barcode (bisa discan via nama atau barcode asli)
        if ($produk->barcode != $request->barcode && !str_contains(strtolower($produk->nama), strtolower($request->barcode))) {
            return back()->with('error', 'Barcode tidak cocok dengan satuan kemasan yang dipilih!');
        }

        $cart = session()->get('cart', []);
        
        // Buat ID unik kombinasi produk dan tingkat kemasannya di dalam keranjang
        $cartId = $produk->id . '-' . $kemasan->id;

        if (isset($cart[$cartId])) {
            $cart[$cartId]['qty'] += $qty;
            $cart[$cartId]['subtotal'] = $cart[$cartId]['qty'] * $cart[$cartId]['harga_satuan'];
        } else {
            $cart[$cartId] = [
                'produk_id'    => $produk->id,
                'kemasan_id'   => $kemasan->id,
                'nama'         => $produk->nama . ' (' . $kemasan->nama . ' - ' . $kemasan->satuan . ')',
                'harga_satuan' => $kemasan->harga_jual,
                'qty'          => $qty,
                'subtotal'     => $kemasan->harga_jual * $qty
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    // 3. Update Qty via AJAX di Tabel
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['qty'] = $request->qty;
            $cart[$request->id]['subtotal'] = $cart[$request->id]['qty'] * $cart[$request->id]['harga_satuan'];
            session()->put('cart', $cart);
        }

        return response()->json(['status' => 'success']);
    }

    // 4. Menghapus Item dari Keranjang
    public function removeCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    // 5. AJAX API pencarian data member
    public function cariMember(Request $request)
    {
        $member = MemberModel::where('kode_member', $request->kode)->first();
        return response()->json($member);
    }

    // 6. Menyimpan Pembelian & Pengurangan Stok Utama Berantai
    public function prosesBayar(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja masih kosong!');
        }

        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['subtotal'];
        }

        $dibayar = $request->dibayar;
        $kembalian = $dibayar - $totalHarga;

        if ($kembalian < 0) {
            return back()->with('error', 'Uang pembayaran tunai kurang!');
        }

        DB::beginTransaction();
        try {
            $noInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(\Str::random(5));

            // Simpan master penjualan
            $penjualan = PenjualanModel::create([
                'no_invoice'  => $noInvoice,
                'user_id'     => Auth::id() ?? 1,
                'member_id'   => $request->member_id, // Terisi jika input member valid
                'total_harga' => $totalHarga,
                'dibayar'     => $dibayar,
                'kembalian'   => $kembalian,
            ]);

            // Olah isi detail belanja & Pengurangan Multi-Kemasan ke Satuan Terkecil
            foreach ($cart as $item) {
                $produk = ProdukModel::findOrFail($item['produk_id']);
                $kemasanIdTerpilih = $item['kemasan_id'];

                // Ambil data konversi berantai produk terkait
                $semuaKemasan = ProdukKemasanModel::where('produk_id', $produk->id)->get();
                $kemasanBesar = $semuaKemasan->where('nama', 'Besar')->first();
                $kemasanSedang = $semuaKemasan->where('nama', 'Sedang')->first();
                $kemasanTerpilih = $semuaKemasan->where('id', $kemasanIdTerpilih)->first();

                $jumlahPotongStokKecil = 0;

                // Hitung faktor pengali berdasarkan level kemasannya
                if ($kemasanTerpilih->nama === 'Besar') {
                    $konversiBesar = $kemasanBesar ? $kemasanBesar->konversi : 1;
                    $konversiSedang = $kemasanSedang ? $kemasanSedang->konversi : 1;
                    $jumlahPotongStokKecil = $item['qty'] * $konversiBesar * $konversiSedang;
                } elseif ($kemasanTerpilih->nama === 'Sedang') {
                    $konversiSedang = $kemasanSedang ? $kemasanSedang->konversi : 1;
                    $jumlahPotongStokKecil = $item['qty'] * $konversiSedang;
                } else {
                    $jumlahPotongStokKecil = $item['qty'];
                }

                // Proteksi batas kuantitas stok di gudang database
                if ($produk->stok < $jumlahPotongStokKecil) {
                    throw new \Exception("Stok barang '{$produk->nama}' tidak cukup. Sisa stok fisik (satuan kecil): {$produk->stok}");
                }

                // Potong stok utama
                $produk->decrement('stok', $jumlahPotongStokKecil);

                // Catat transaksi detail (Gunakan Query Builder jika model detail belum siap)
                DB::table('detail_penjualans')->insert([
                    'penjualan_id'       => $penjualan->id,
                    'produk_id'          => $produk->id,
                    'produk_kemasan_id'  => $kemasanTerpilih->id, // Menggunakan ID kemasan sesuai tabel foreignId
                    'jumlah'             => $item['qty'],          // Kolom 'qty' diganti 'jumlah' sesuai migration
                    'harga_satuan'       => $item['harga_satuan'], // Harga jual yang berlaku saat transaksi
                    'harga_beli'         => $kemasanTerpilih->harga_beli ?? 0, // Pastikan field harga_beli diisi (ambil dari master kemasan)
                    'subtotal'           => $item['subtotal'],
                    'konversi_saat_itu'  => $kemasanTerpilih->konversi, // Rekam nilai konversi untuk audit stock nanti
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            DB::commit();

            // Bersihkan isi session belanja kasir
            session()->forget('cart');

            // Redirect & picu print otomatis dengan melampirkan session ID baru
            return redirect()->route('transaksi')
                ->with('success', 'Transaksi Berhasil disimpan!')
                ->with('print_penjualan_id', $penjualan->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    // 7. Mengambil Data Cetak Struk
    public function cetakStruk($id)
    {
        $penjualan = PenjualanModel::with(['member', 'user'])->findOrFail($id);
        
        $details = DB::table('detail_penjualans')
            ->join('produks', 'detail_penjualans.produk_id', '=', 'produks.id')
            // Join ke tabel kemasan agar nama kemasan (Besar/Sedang/Kecil) bisa dicetak di struk
            ->join('produk_kemasans', 'detail_penjualans.produk_kemasan_id', '=', 'produk_kemasans.id') 
            ->where('detail_penjualans.penjualan_id', $id)
            ->select(
                'detail_penjualans.*', 
                'produks.nama as nama_produk',
                'produk_kemasans.nama as nama_kemasan',
                'produk_kemasans.satuan as satuan_kemasan'
            )
            ->get();

        return view('kasir.struk', compact('penjualan', 'details'));
    }
}