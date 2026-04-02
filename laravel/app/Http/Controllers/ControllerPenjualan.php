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
        $request->validate([
            'barcode' => 'required',
            'kemasan_id' => 'required|exists:produk_kemasan,id',
        ]);

        $kemasan = ProdukKemasanModel::with('produk')->find($request->kemasan_id);
        if (!$kemasan) {
            return back()->with('error', 'Kemasan tidak valid');
        }

        $produk = $kemasan->produk;
        // Cek stok cukup tidak? (optional, bisa dicek nanti saat bayar)
        // if ($produk->stok < $kemasan->konversi) return back()->with('error', 'Stok tidak cukup');

        $cart = session()->get('cart', []);
        $key = $produk->id . '_' . $kemasan->id;
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
            $cart[$key]['subtotal'] = $cart[$key]['qty'] * $kemasan->harga_jual;
        } else {
            $cart[$key] = [
                'produk_id' => $produk->id,
                'kemasan_id' => $kemasan->id,
                'nama' => $produk->nama . ' (' . $kemasan->nama . ')',
                'harga_satuan' => $kemasan->harga_jual,
                'qty' => 1,
                'subtotal' => $kemasan->harga_jual,
                'konversi_pcs' => $kemasan->konversi,
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('transaksi')->with('success', 'Produk ditambahkan');
    }

    public function prosesBayar(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) return back()->with('error', 'Keranjang kosong');

        $total = array_sum(array_column($cart, 'subtotal'));
        $dibayar = $request->dibayar;
        if ($dibayar < $total) return back()->with('error', 'Uang kurang');

        DB::beginTransaction();
        try {
            $noInvoice = 'INV/' . date('Ymd') . '/' . rand(1000, 9999);
            $penjualan = PenjualanModel::create([
                'no_invoice' => $noInvoice,
                'user_id' => Auth::id(),
                'member_id' => $request->member_id,
                'total_harga' => $total,
                'dibayar' => $dibayar,
                'kembalian' => $dibayar - $total,
            ]);

            foreach ($cart as $item) {
                DetailPenjualanModel::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'],
                ]);

                $produk = ProdukModel::find($item['produk_id']);
                $totalPcs = $item['qty'] * $item['konversi_pcs'];
                $produk->stok -= $totalPcs;
                $produk->save();
            }

            // tambah poin member jika ada...
            session()->forget('cart');
            DB::commit();
            return redirect()->route('struk', $penjualan->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
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
        if ($qty <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['qty'] = $qty;
            $cart[$id]['subtotal'] = $cart[$id]['harga'] * $qty;
        }
        session()->put('cart', $cart);
        return response()->json(['success' => true]);
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
