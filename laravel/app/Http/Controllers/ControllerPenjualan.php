<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\MemberModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProdukKemasanModel;

class ControllerPenjualan extends Controller
{
    public function scan(Request $request)
    {
        $kemasan = ProdukKemasanModel::where('id', $request->kemasan_id)->first();
        $produk = ProdukModel::find($kemasan->produk_id);
        
        // Ambil SEMUA kemasan untuk produk ini agar bisa menghitung rantai konversi
        $semuaKemasan = ProdukKemasanModel::where('produk_id', $produk->id)->get();
        $konversiBal = $semuaKemasan->where('nama', 'Besar')->first()->konversi ?? 1;
        $konversiSlop = $semuaKemasan->where('nama', 'Sedang')->first()->konversi ?? 1;

        // HITUNG TOTAL PCS PER SATUAN YANG DIPILIH
        $pcsPerUnit = 1;
        if ($kemasan->nama == 'Besar') {
            $pcsPerUnit = $konversiBal * $konversiSlop; // 20 * 10 = 200
        } elseif ($kemasan->nama == 'Sedang') {
            $pcsPerUnit = $konversiSlop; // 10
        }

        $totalPcsDibutuhkan = $request->qty * $pcsPerUnit;

        // Cek Stok Gudang
        if ($produk->stok < $totalPcsDibutuhkan) {
            return back()->with('error', "Stok kurang! Butuh $totalPcsDibutuhkan Pack, cuma ada $produk->stok Pack.");
        }

        // Simpan ke Cart
        $cart = session()->get('cart', []);
        $key = $produk->id . '_' . $kemasan->id;
        $cart[$key] = [
            'produk_id'    => $produk->id,
            'kemasan_id'   => $kemasan->id,
            'nama'         => $produk->nama . " ({$kemasan->satuan})",
            'harga_satuan' => $kemasan->harga_jual,
            'qty'          => $request->qty,
            'subtotal'     => $kemasan->harga_jual * $request->qty,
            'konversi_total_pcs' => $pcsPerUnit, // Simpan hasil akhir (200, 10, atau 1)
        ];

        session()->put('cart', $cart);
        return redirect()->route('transaksi');
    }

    public function prosesBayar(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) return back()->with('error', 'Keranjang kosong');

        $total = array_sum(array_column($cart, 'subtotal'));
        $dibayar = $request->dibayar;
        if ($dibayar < $total) return back()->with('error', 'Uang pembayaran kurang');

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::create([
                'no_invoice'  => 'INV/' . date('Ymd') . '/' . rand(1000, 9999),
                'user_id'     => Auth::id(),
                'member_id'   => $request->member_id,
                'total_harga' => $total,
                'dibayar'     => $dibayar,
                'kembalian'   => $dibayar - $total,
            ]);

            foreach ($cart as $item) {
                $kemasanData = ProdukKemasanModel::findOrFail($item['kemasan_id']);

                DetailPenjualanModel::create([
                    'penjualan_id'      => $penjualan->id,
                    'produk_id'         => $item['produk_id'],
                    'produk_kemasan_id' => $item['kemasan_id'],
                    'jumlah'            => $item['qty'],
                    'harga_satuan'      => $item['harga_satuan'],
                    'harga_beli'        => $kemasanData->harga_beli,
                    'subtotal'          => $item['subtotal'],
                    // TAMBAHKAN BARIS DI BAWAH INI
                    'konversi_saat_itu' => $item['konversi_total_pcs'], 
                ]);

                // Potong stok produk
                $produk = ProdukModel::find($item['produk_id']);
                $produk->decrement('stok', ($item['qty'] * $item['konversi_total_pcs']));
            }

            DB::commit();
            session()->forget('cart');
            return redirect()->route('struk', $penjualan->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_column($cart, 'subtotal'));
        $daftarKemasan = \App\Models\ProdukKemasanModel::with('produk')->get(); // atau sesuai kebutuhan
        return view('kasir.transaksi', compact('cart', 'total', 'daftarKemasan'));
    }

    
    // Update jumlah item di keranjang
    public function updateCart(Request $request)
    {
        $id = $request->id;
        $qty = $request->qty;
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                // VALIDASI STOK ULANG
                $produk = ProdukModel::find($cart[$id]['produk_id']);
                $totalPcsDibutuhkan = $qty * $cart[$id]['konversi_total_pcs']; 

                if ($produk->stok < $totalPcsDibutuhkan) {
                    return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi!']);
                }

                $cart[$id]['qty'] = $qty;
                // Gunakan harga_satuan (sesuai field di fungsi scan)
                $cart[$id]['subtotal'] = $cart[$id]['harga_satuan'] * $qty;
            }
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Item tidak ditemukan']);
    }

    // Hapus item dari keranjang
    public function removeCart($id)
    {
        $cart = session()->get('cart');
        unset($cart[$id]);
        session()->put('cart', $cart);
        return redirect()->route('transaksi')->with('success', 'Item dihapus');
    }

    // Cari member (AJAX)
    public function cariMember(Request $request)
    {
        $kode = $request->kode;
        $member = MemberModel::where('kode_member', $kode)->first();
        if ($member) {
            return response()->json($member);
        }
        return response()->json(null);
    }

    // Cetak struk
    public function struk($id)
    {
        $penjualan = PenjualanModel::with(['user', 'member', 'details.produk'])->findOrFail($id);
        return view('kasir.struk', compact('penjualan'));
    }
}
