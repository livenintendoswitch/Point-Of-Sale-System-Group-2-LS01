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
            'nama'    => 'required',
            'stok'    => 'required|numeric', // Menangkap data dari input hidden

            'konversi_besar'  => 'required|integer|min:1', 
            'konversi_sedang' => 'required|integer|min:1', 
            
            'harga_beli_besar' => 'required|numeric',
            'harga_jual_besar' => 'required|numeric',
            'harga_beli_sedang'=> 'required|numeric',
            'harga_jual_sedang'=> 'required|numeric',
            'harga_beli_kecil' => 'required|numeric',
            'harga_jual_kecil' => 'required|numeric',
        ]);

        $konversiBesar  = $request->konversi_besar;  
        $konversiSedang = $request->konversi_sedang; 

        // Ambil data stok langsung dari input 'stok' (kiriman input hidden HTML)
        // Jika karena suatu hal bernilai null/0, jalankan hitung manual sebagai backup
        $stokFinal = $request->stok;

        if ($stokFinal == 0 || $stokFinal == null) {
            $stokBesar  = $request->input('stok_besar', 0) ?? 0;
            $stokSedang = $request->input('stok_sedang', 0) ?? 0;
            $stokKecil  = $request->input('stok_kecil', 0) ?? 0;

            $totalUnitSedang = ($stokBesar * $konversiBesar) + $stokSedang;
            $stokFinal       = ($totalUnitSedang * $konversiSedang) + $stokKecil;
        }

        \DB::beginTransaction();
        try {
            $produk = ProdukModel::create([
                'barcode' => $request->barcode,
                'nama'    => $request->nama,
                'stok'    => $stokFinal, // Menggunakan hasil akhir yang valid (Bukan 0 lagi)
            ]);

            ProdukKemasanModel::create([
                'produk_id'  => $produk->id,
                'nama'       => 'Besar',
                'satuan'     => 'Bal',
                'konversi'   => $konversiBesar, 
                'harga_beli' => $request->harga_beli_besar,
                'harga_jual' => $request->harga_jual_besar,
            ]);

            ProdukKemasanModel::create([
                'produk_id'  => $produk->id,
                'nama'       => 'Sedang',
                'satuan'     => 'Slop',
                'konversi'   => $konversiSedang, 
                'harga_beli' => $request->harga_beli_sedang,
                'harga_jual' => $request->harga_jual_sedang,
            ]);

            ProdukKemasanModel::create([
                'produk_id'  => $produk->id,
                'nama'       => 'Kecil',
                'satuan'     => 'Pack',
                'konversi'   => 1,
                'harga_beli' => $request->harga_beli_kecil,
                'harga_jual' => $request->harga_jual_kecil,
            ]);

            \DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan dengan konversi berantai!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage())->withInput();
        }
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

        // 1. Validasi Input Form Edit
        $request->validate([
            'barcode' => 'required|unique:produks,barcode,' . $id,
            'nama'    => 'required',
            'stok'    => 'required|integer|min:0', // Menangkap total stok terupdate dari input hidden
            
            // Validasi array kemasan
            'kemasan_nama'      => 'required|array|min:1',
            'kemasan_nama.*'    => 'required|string',
            'kemasan_satuan'    => 'required|array',
            'kemasan_satuan.*'  => 'required|string',
            'kemasan_konversi'  => 'required|array',
            'kemasan_konversi.*'=> 'required|integer|min:1',
            'kemasan_harga_beli'=> 'required|array',
            'kemasan_harga_beli.*'=> 'required|numeric|min:0',
            'kemasan_harga_jual'=> 'required|array',
            'kemasan_harga_jual.*'=> 'required|numeric|min:0',
        ]);

        \DB::beginTransaction();
        try {
            // 2. Update data produk utama (termasuk total stoknya)
            $produk->update([
                'barcode' => $request->barcode,
                'nama'    => $request->nama,
                'stok'    => $request->stok,
            ]);

            // 3. Hapus kemasan lama, lalu tulis ulang yang baru
            $produk->kemasan()->delete();

            foreach ($request->kemasan_nama as $key => $nama) {
                ProdukKemasanModel::create([
                    'produk_id'  => $produk->id,
                    'nama'       => $nama,                                 // Besar / Sedang / Kecil
                    'satuan'     => $request->kemasan_satuan[$key],        // Bal / Slop / Pack (INI YANG MEMBUAT ERROR)
                    'konversi'   => $request->kemasan_konversi[$key],      // 20 / 10 / 1
                    'harga_beli' => $request->kemasan_harga_beli[$key],    // Harga Beli baru
                    'harga_jual' => $request->kemasan_harga_jual[$key],    // Harga Jual baru
                ]);
            }

            \DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk dan kemasan berhasil diperbarui!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }

    // Hapus produk (beserta kemasan karena cascade)
    public function destroy($id)
    {
        $produk = ProdukModel::findOrFail($id);
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk dihapus');
    }
}
