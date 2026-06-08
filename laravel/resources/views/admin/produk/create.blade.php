@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    
    <form action="{{ route('produk.store') }}" method="POST">
        @csrf

        <div class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Tambah Produk & Kemasan Bertingkat</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Barcode</label>
                    <input type="text" name="barcode" class="w-full border border-slate-300 rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama" class="w-full border border-slate-300 rounded-lg p-2" required>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-slate-700 mb-3">Pengaturan Kemasan & Stok</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-slate-200">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 text-sm font-semibold">
                            <th class="p-3 border">Level</th>
                            <th class="p-3 border">Nama Satuan</th>
                            <th class="p-3 border">Konversi Isi</th>
                            <th class="p-3 border">Input Stok</th>
                            <th class="p-3 border">Harga Beli</th>
                            <th class="p-3 border">Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr class="bg-blue-50/50">
                            <td class="p-3 border font-bold text-blue-700">Besar</td>
                            <td class="p-3 border">
                                <input type="text" id="satuan_besar" name="satuan_besar" value="Bal" class="w-24 border border-slate-300 rounded p-1 text-center">
                            </td>
                            <td class="p-3 border">
                                <div class="flex items-center gap-2">
                                    <input type="number" id="konversi_besar" name="konversi_besar" value="20" class="w-20 border border-slate-300 rounded p-1 text-center">
                                    <span id="lbl_konversi_besar" class="text-slate-500 font-medium text-xs">Slop</span>
                                </div>
                            </td>
                            <td class="p-3 border">
                                <input type="number" id="stok_besar" name="stok_besar" value="2" class="w-20 border border-slate-300 rounded p-1 text-center">
                            </td>
                            <td class="p-3 border"><input type="number" name="harga_beli_besar" class="w-full border border-slate-300 rounded p-1" required></td>
                            <td class="p-3 border"><input type="number" name="harga_jual_besar" class="w-full border border-slate-300 rounded p-1" required></td>
                        </tr>

                        <tr class="bg-emerald-50/50">
                            <td class="p-3 border font-bold text-emerald-700">Sedang</td>
                            <td class="p-3 border">
                                <input type="text" id="satuan_sedang" name="satuan_sedang" value="Slop" class="w-24 border border-slate-300 rounded p-1 text-center">
                            </td>
                            <td class="p-3 border">
                                <div class="flex items-center gap-2">
                                    <input type="number" id="konversi_sedang" name="konversi_sedang" value="10" class="w-20 border border-slate-300 rounded p-1 text-center">
                                    <span id="lbl_konversi_sedang" class="text-slate-500 font-medium text-xs">Pak</span>
                                </div>
                            </td>
                            <td class="p-3 border">
                                <input type="number" id="stok_sedang" name="stok_sedang" value="5" class="w-20 border border-slate-300 rounded p-1 text-center">
                            </td>
                            <td class="p-3 border"><input type="number" name="harga_beli_sedang" class="w-full border border-slate-300 rounded p-1" required></td>
                            <td class="p-3 border"><input type="number" name="harga_jual_sedang" class="w-full border border-slate-300 rounded p-1" required></td>
                        </tr>

                        <tr class="bg-amber-50/50">
                            <td class="p-3 border font-bold text-amber-700">Kecil</td>
                            <td class="p-3 border">
                                <input type="text" id="satuan_kecil" name="satuan_kecil" value="Pak" class="w-24 border border-slate-300 rounded p-1 text-center">
                            </td>
                            <td class="p-3 border">
                                <div class="flex items-center gap-2">
                                    <input type="number" id="konversi_kecil" name="konversi_kecil" value="1" readonly class="w-20 bg-slate-100 border border-slate-300 rounded p-1 text-center text-slate-400">
                                    <span class="text-slate-400 font-medium text-xs">Pcs</span>
                                </div>
                            </td>
                            <td class="p-3 border">
                                <input type="number" id="stok_kecil" name="stok_kecil" value="0" class="w-20 border border-slate-300 rounded p-1 text-center">
                            </td>
                            <td class="p-3 border"><input type="number" name="harga_beli_kecil" class="w-full border border-slate-300 rounded p-1" required></td>
                            <td class="p-3 border"><input type="number" name="harga_jual_kecil" class="w-full border border-slate-300 rounded p-1" required></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-800 p-4 rounded-xl flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                <div class="text-white font-medium text-sm">
                    Total Stok Akhir: <span id="total_stok_akhir" class="text-emerald-400 font-bold text-xl">450</span> <span id="lbl_satuan_akhir" class="text-emerald-400 font-bold text-xl">Pak</span>
                    <input type="hidden" name="stok" id="stok_total_hidden" value="0">
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('produk.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-colors">Batal / Kembali</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg text-sm transition-colors shadow-md">SIMPAN PRODUK</button>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Pengambilan element DOM menggunakan ID
    const satuanBesar = document.getElementById('satuan_besar');
    const satuanSedang = document.getElementById('satuan_sedang');
    const satuanKecil = document.getElementById('satuan_kecil');

    const lblKonversiBesar = document.getElementById('lbl_konversi_besar');
    const lblKonversiSedang = document.getElementById('lbl_konversi_sedang');
    const lblSatuanAkhir = document.getElementById('lbl_satuan_akhir');

    const konversiBesar = document.getElementById('konversi_besar');
    const konversiSedang = document.getElementById('konversi_sedang');

    const stokBesar = document.getElementById('stok_besar');
    const stokSedang = document.getElementById('stok_sedang');
    const stokKecil = document.getElementById('stok_kecil');
    
    const totalStokAkhir = document.getElementById('total_stok_akhir');

    // Pengaman fungsi label
    function updateLabels() {
        if (lblKonversiBesar && satuanSedang) lblKonversiBesar.textContent = satuanSedang.value || 'Sedang';
        if (lblKonversiSedang && satuanKecil) lblKonversiSedang.textContent = satuanKecil.value || 'Kecil';
        if (lblSatuanAkhir && satuanKecil) lblSatuanAkhir.textContent = satuanKecil.value || 'Pcs';
    }

    // Pengaman fungsi matematika berantai
    function hitungStokTotal() {
        const kBesar  = konversiBesar ? (parseInt(konversiBesar.value) || 0) : 0;
        const kSedang = konversiSedang ? (parseInt(konversiSedang.value) || 0) : 0;

        const sBesar  = stokBesar ? (parseInt(stokBesar.value) || 0) : 0;
        const sSedang = stokSedang ? (parseInt(stokSedang.value) || 0) : 0;
        const sKecil  = stokKecil ? (parseInt(stokKecil.value) || 0) : 0;

        // Hitung: (Besar * KonversiBesar) + Sedang -> dikalikan KonversiSedang + Eceran Kecil
        const totalUnitSedang = (sBesar * kBesar) + sSedang; 
        const hasilAkhirPcs = (totalUnitSedang * kSedang) + sKecil; 

        if (totalStokAkhir) {
            totalStokAkhir.textContent = hasilAkhirPcs.toLocaleString('id-ID');
        }
    }

    // Pemasangan Event Listener dengan validasi existensi elemen
    if (satuanBesar) satuanBesar.addEventListener('input', updateLabels);
    if (satuanSedang) satuanSedang.addEventListener('input', updateLabels);
    if (satuanKecil) satuanKecil.addEventListener('input', updateLabels);

    [konversiBesar, konversiSedang, stokBesar, stokSedang, stokKecil].forEach(el => {
        if (el) el.addEventListener('input', hitungStokTotal);
    });

    // Inisialisasi awal
    updateLabels();
    hitungStokTotal();
});
</script>
@endsection