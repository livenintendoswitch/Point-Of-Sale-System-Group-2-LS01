@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>
    <form method="POST" action="{{ route('produk.update', $produk->id) }}">
        @csrf @method('PUT')
        <div class="mb-4">
            <label>Barcode</label>
            <input type="text" name="barcode" value="{{ $produk->barcode }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label>Nama Produk</label>
            <input type="text" name="nama" value="{{ $produk->nama }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label>Stok (PCS)</label>
            <input type="number" name="stok" value="{{ $produk->stok }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <h2 class="text-xl font-bold mt-6 mb-4">Daftar Kemasan</h2>
        <div id="kemasan-wrapper">
            @foreach($produk->kemasan as $kemasan)
            <div class="kemasan-item grid grid-cols-4 gap-4 mb-4">
                <input type="text" name="kemasan_nama[]" value="{{ $kemasan->nama }}" class="border rounded px-3 py-2" required>
                <input type="number" name="kemasan_konversi[]" value="{{ $kemasan->konversi }}" class="border rounded px-3 py-2" required>
                <input type="number" name="kemasan_harga[]" value="{{ $kemasan->harga_jual }}" class="border rounded px-3 py-2" required>
                <button type="button" class="remove-kemasan bg-red-500 text-white px-3 py-2 rounded">Hapus</button>
            </div>
            @endforeach
        </div>
        <button type="button" id="tambah-kemasan" class="bg-green-500 text-white px-4 py-2 rounded mb-4">+ Tambah Kemasan</button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>

<script>
    document.getElementById('tambah-kemasan').addEventListener('click', function() {
        let wrapper = document.getElementById('kemasan-wrapper');
        let newItem = document.createElement('div');
        newItem.className = 'kemasan-item grid grid-cols-4 gap-4 mb-4';
        newItem.innerHTML = `
            <input type="text" name="kemasan_nama[]" placeholder="Nama kemasan" class="border rounded px-3 py-2" required>
            <input type="number" name="kemasan_konversi[]" placeholder="Konversi (pcs)" class="border rounded px-3 py-2" required>
            <input type="number" name="kemasan_harga[]" placeholder="Harga jual" class="border rounded px-3 py-2" required>
            <button type="button" class="remove-kemasan bg-red-500 text-white px-3 py-2 rounded">Hapus</button>
        `;
        wrapper.appendChild(newItem);
        attachRemoveEvent(newItem.querySelector('.remove-kemasan'));
    });

    function attachRemoveEvent(btn) {
        btn.addEventListener('click', function() {
            this.closest('.kemasan-item').remove();
        });
    }

    document.querySelectorAll('.remove-kemasan').forEach(btn => attachRemoveEvent(btn));
</script>
@endsection