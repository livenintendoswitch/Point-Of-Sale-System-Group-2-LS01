<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\ProdukKemasanModel;

class ControllerProduk extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $produks = ProdukModel::with('kemasan')->latest()->paginate(10);
        return view('admin.produk.index', compact('produks'));
    }

    // Form tambah produk
    public function create()
    {
        return view('admin.produk.create');
    }

    // Simpan produk beserta kemasan
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:produks,barcode',
            'nama' => 'required',
            'stok' => 'required|integer|min:0',
            'kemasan_nama' => 'required|array|min:1',
            'kemasan_nama.*' => 'required|string',
            'kemasan_konversi' => 'required|array',
            'kemasan_konversi.*' => 'required|integer|min:1',
            'kemasan_harga' => 'required|array',
            'kemasan_harga.*' => 'required|integer|min:0',
        ]);

        $produk = ProdukModel::create([
            'barcode' => $request->barcode,
            'nama' => $request->nama,
            'stok' => $request->stok,
            'harga' => 0, // tidak dipakai, bisa diisi 0
        ]);

        foreach ($request->kemasan_nama as $key => $nama) {
            ProdukKemasanModel::create([
                'produk_id' => $produk->id,
                'nama' => $nama,
                'konversi' => $request->kemasan_konversi[$key],
                'harga_jual' => $request->kemasan_harga[$key],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk dan kemasan berhasil ditambahkan');
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = ProdukModel::with('kemasan')->findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    // Update produk dan kemasan
    public function update(Request $request, $id)
    {
        $produk = ProdukModel::findOrFail($id);

        $request->validate([
            'barcode' => 'required|unique:produks,barcode,' . $id,
            'nama' => 'required',
            'stok' => 'required|integer|min:0',
            'kemasan_nama' => 'required|array|min:1',
            'kemasan_nama.*' => 'required|string',
            'kemasan_konversi' => 'required|array',
            'kemasan_konversi.*' => 'required|integer|min:1',
            'kemasan_harga' => 'required|array',
            'kemasan_harga.*' => 'required|integer|min:0',
        ]);

        $produk->update([
            'barcode' => $request->barcode,
            'nama' => $request->nama,
            'stok' => $request->stok,
        ]);

        // Hapus kemasan lama, buat ulang
        $produk->kemasan()->delete();
        foreach ($request->kemasan_nama as $key => $nama) {
            ProdukKemasanModel::create([
                'produk_id' => $produk->id,
                'nama' => $nama,
                'konversi' => $request->kemasan_konversi[$key],
                'harga_jual' => $request->kemasan_harga[$key],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk diupdate');
    }

    // Hapus produk (beserta kemasan karena cascade)
    public function destroy($id)
    {
        $produk = ProdukModel::findOrFail($id);
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk dihapus');
    }
}
