@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6 border border-slate-200">
        <h1 class="text-2xl font-bold mb-4 text-slate-800">Edit Produk & Kemasan</h1>
        
        <form method="POST" action="{{ route('produk.update', $produk->id) }}">
            @csrf 
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Penyebab Gagal Simpan:</strong>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Barcode</label>
                    <input type="text" name="barcode" value="{{ old('barcode', $produk->barcode) }}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Total Stok Berantai (PCS)</label>
                    <input type="number" name="stok" id="stok_total" value="{{ old('stok', $produk->stok) }}" class="w-full border rounded-lg px-3 py-2 bg-slate-50 font-bold text-emerald-600" required>
                </div>
            </div>

            <h2 class="text-xl font-bold mt-6 mb-3 text-slate-700">Daftar Tingkat Kemasan</h2>
            
            <div class="overflow-x-auto mb-4">
                <table class="w-full text-left border-collapse border border-slate-200">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 text-sm font-semibold">
                            <th class="p-3 border">Level (Nama)</th>
                            <th class="p-3 border">Nama Satuan</th>
                            <th class="p-3 border">Konversi Isi</th>
                            <th class="p-3 border">Harga Beli</th>
                            <th class="p-3 border">Harga Jual</th>
                            <th class="p-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kemasan-wrapper" class="text-sm">
                        @foreach($produk->kemasan as $kemasan)
                        <tr class="kemasan-item bg-white">
                            <td class="p-2 border">
                                <input type="text" name="kemasan_nama[]" value="{{ $kemasan->nama }}" class="w-full border rounded p-1 font-semibold text-slate-700 bg-slate-50" readonly required>
                            </td>
                            <td class="p-2 border">
                                <input type="text" name="kemasan_satuan[]" value="{{ $kemasan->satuan }}" placeholder="ex: Bal/Slop" class="w-full border rounded p-1" required>
                            </td>
                            <td class="p-2 border">
                                <input type="number" name="kemasan_konversi[]" value="{{ $kemasan->konversi }}" class="w-full border rounded p-1 text-center" {{ $kemasan->nama == 'Kecil' ? 'readonly bg-slate-100' : '' }} required>
                            </td>
                            <td class="p-2 border">
                                <input type="number" name="kemasan_harga_beli[]" value="{{ $kemasan->harga_beli ?? 0 }}" class="w-full border rounded p-1" required>
                            </td>
                            <td class="p-2 border">
                                <input type="number" name="kemasan_harga_jual[]" value="{{ $kemasan->harga_jual }}" class="w-full border rounded p-1" required>
                            </td>
                            <td class="p-2 border text-center">
                                @if($kemasan->nama != 'Kecil')
                                    <button type="button" class="remove-kemasan bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Hapus</button>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Dasar Utama</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="button" id="tambah-kemasan" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">+ Tambah Level Kemasan</button>
                
                <div class="flex gap-3">
                    <a href="{{ route('produk.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">Batal / Kembali</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-md transition-colors">Update & Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle Tambah Baris Kemasan Baru Dinamis
    document.getElementById('tambah-kemasan').addEventListener('click', function() {
        let wrapper = document.getElementById('kemasan-wrapper');
        let tr = document.createElement('tr');
        tr.className = 'kemasan-item bg-white';
        tr.innerHTML = `
            <td class="p-2 border">
                <input type="text" name="kemasan_nama[]" placeholder="ex: Kustom" class="w-full border rounded p-1" required>
            </td>
            <td class="p-2 border">
                <input type="text" name="kemasan_satuan[]" placeholder="ex: Dus/Box" class="w-full border rounded p-1" required>
            </td>
            <td class="p-2 border">
                <input type="number" name="kemasan_konversi[]" placeholder="Isi" class="w-full border rounded p-1 text-center" required>
            </td>
            <td class="p-2 border">
                <input type="number" name="kemasan_harga_beli[]" value="0" class="w-full border rounded p-1" required>
            </td>
            <td class="p-2 border">
                <input type="number" name="kemasan_harga_jual[]" value="0" class="w-full border rounded p-1" required>
            </td>
            <td class="p-2 border text-center">
                <button type="button" class="remove-kemasan bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Hapus</button>
            </td>
        `;
        wrapper.appendChild(tr);
        attachRemoveEvent(tr.querySelector('.remove-kemasan'));
    });

    function attachRemoveEvent(btn) {
        btn.addEventListener('click', function() {
            this.closest('.kemasan-item').remove();
        });
    }

    document.querySelectorAll('.remove-kemasan').forEach(btn => attachRemoveEvent(btn));
</script>
@endsection