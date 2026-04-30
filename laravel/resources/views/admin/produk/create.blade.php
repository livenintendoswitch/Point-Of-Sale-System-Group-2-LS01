@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Tambah Produk & Kemasan Bertingkat</h1>
    <form method="POST" action="{{ route('produk.store') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-semibold">Barcode</label>
                <input type="text" name="barcode" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold">Nama Produk</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <h2 class="text-xl font-bold mb-4 border-b pb-2">Pengaturan Kemasan & Stok</h2>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Level</th>
                    <th class="p-2 border">Nama Satuan (cth: Dos)</th>
                    <th class="p-2 border">Konversi (Isi ke PCS)</th>
                    <th class="p-2 border">Input Stok</th>
                    <th class="p-2 border">Harga Beli</th>
                    <th class="p-2 border">Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                <!-- BESAR -->
                <tr class="bg-blue-50">
                    <td class="p-2 border font-bold text-blue-700">Besar</td>
                    <td class="p-2 border"><input type="text" name="satuan[besar]" placeholder="Dos" class="w-full p-1 border rounded" required></td>
                    <td class="p-2 border"><input type="number" name="konversi[besar]" id="konv_besar" value="200" class="w-full p-1 border rounded hitung-stok" required></td>
                    <td class="p-2 border"><input type="number" name="stok_input[besar]" id="stok_besar" value="0" class="w-full p-1 border rounded hitung-stok"></td>
                    <td class="p-2 border"><input type="number" name="harga_beli[besar]" class="w-full p-1 border rounded" required></td>
                    <td class="p-2 border"><input type="number" name="harga_jual[besar]" class="w-full p-1 border rounded" required></td>
                </tr>
                <!-- SEDANG -->
                <tr class="bg-green-50">
                    <td class="p-2 border font-bold text-green-700">Sedang</td>
                    <td class="p-2 border"><input type="text" name="satuan[sedang]" placeholder="Slop" class="w-full p-1 border rounded" required></td>
                    <td class="p-2 border"><input type="number" name="konversi[sedang]" id="konv_sedang" value="10" class="w-full p-1 border rounded hitung-stok" required></td>
                    <td class="p-2 border"><input type="number" name="stok_input[sedang]" id="stok_sedang" value="0" class="w-full p-1 border rounded hitung-stok"></td>
                    <td class="p-2 border"><input type="number" name="harga_beli[sedang]" class="w-full p-1 border rounded" required></td>
                    <td class="p-2 border"><input type="number" name="harga_jual[sedang]" class="w-full p-1 border rounded" required></td>
                </tr>
                <!-- KECIL -->
                <tr class="bg-yellow-50">
                    <td class="p-2 border font-bold text-yellow-700">Kecil</td>
                    <td class="p-2 border"><input type="text" name="satuan[kecil]" placeholder="Pcs" class="w-full p-1 border rounded" required></td>
                    <td class="p-2 border"><input type="number" name="konversi[kecil]" id="konv_kecil" value="1" readonly class="w-full p-1 border bg-gray-100 rounded"></td>
                    <td class="p-2 border"><input type="number" name="stok_input[kecil]" id="stok_kecil" value="0" class="w-full p-1 border rounded hitung-stok"></td>
                    <td class="p-2 border"><input type="number" name="harga_beli[kecil]" class="w-full p-1 border rounded" required></td>
                    <td class="p-2 border"><input type="number" name="harga_jual[kecil]" class="w-full p-1 border rounded" required></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6 p-4 bg-gray-800 text-white rounded flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <span class="text-lg">Total Stok Akhir: </span>
                <span id="total-stok-display" class="text-2xl font-bold text-green-400">0</span> PCS
                <input type="hidden" name="stok_total" id="stok_total_hidden" value="0">
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                <!-- TOMBOL KEMBALI -->
                <a href="{{ route('produk.index') }}" 
                class="flex-1 md:flex-none text-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded font-bold transition">
                    Batal / Kembali
                </a>

                <!-- TOMBOL SIMPAN -->
                <button type="submit" 
                        class="flex-1 md:flex-none bg-blue-500 hover:bg-blue-600 text-white px-8 py-2 rounded font-bold shadow-lg transition">
                    SIMPAN PRODUK
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const inputs = document.querySelectorAll('.hitung-stok');
    
    function hitungTotal() {
        let b = (parseInt(document.getElementById('stok_besar').value) || 0) * (parseInt(document.getElementById('konv_besar').value) || 0);
        let s = (parseInt(document.getElementById('stok_sedang').value) || 0) * (parseInt(document.getElementById('konv_sedang').value) || 0);
        let k = (parseInt(document.getElementById('stok_kecil').value) || 0);
        
        let total = b + s + k;
        document.getElementById('total-stok-display').innerText = total;
        document.getElementById('stok_total_hidden').value = total;
    }

    inputs.forEach(input => {
        input.addEventListener('input', hitungTotal);
    });
</script>
@endsection