@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 p-6 font-sans">
    <div class="max-w-7xl mx-auto">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Analytics</h1>
            <p class="text-sm text-slate-500">Laporan Bulanan & Aktivitas Kontribusi Member</p>
        </div>
        
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
            <form action="{{ route('laporan.bulanan') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                    <select name="bulan" class="w-full bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tahun</label>
                    <select name="tahun" class="w-full bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        @foreach(range(date('Y')-5, date('Y')) as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Filter Data
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-emerald-500 text-white rounded-xl p-6 shadow-sm flex items-center justify-between transition-transform hover:scale-[1.02] duration-200">
                <div>
                    <p class="text-sm font-medium text-emerald-100 uppercase tracking-wider">Total Omset</p>
                    <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($ringkasan->total_omset ?? 0) }}</h3>
                    <p class="text-xs text-emerald-100 mt-2 flex items-center">
                        <span class="inline-block mr-1">▲</span> Bulan Ini
                    </p>
                </div>
                <div class="p-3 bg-emerald-600 bg-opacity-40 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>

            <div class="bg-rose-500 text-white rounded-xl p-6 shadow-sm flex items-center justify-between transition-transform hover:scale-[1.02] duration-200">
                <div>
                    <p class="text-sm font-medium text-rose-100 uppercase tracking-wider">Total Laba Bersih</p>
                    <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($ringkasan->total_laba ?? 0) }}</h3>
                    <p class="text-xs text-rose-100 mt-2 flex items-center">
                        <span class="inline-block mr-1">▲</span> Margin Keuntungan
                    </p>
                </div>
                <div class="p-3 bg-rose-600 bg-opacity-40 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>

            <div class="bg-amber-500 text-white rounded-xl p-6 shadow-sm flex items-center justify-between transition-transform hover:scale-[1.02] duration-200">
                <div>
                    <p class="text-sm font-medium text-amber-100 uppercase tracking-wider">Member Bertransaksi</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $laporanMember->count() }} <span class="text-lg font-normal">Orang</span></h3>
                    <p class="text-xs text-amber-100 mt-2 flex items-center">
                        <span class="inline-block mr-1">●</span> Aktivitas Belanja
                    </p>
                </div>
                <div class="p-3 bg-amber-600 bg-opacity-40 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-white">Rincian Kontribusi Member</h2>
                <span class="bg-slate-700 text-slate-300 text-xs px-2.5 py-1 rounded-full font-medium">Live Data</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Member (Kode)</th>
                            <th class="px-6 py-4">Total Belanja</th>
                            <th class="px-6 py-4">Laba bagi Toko</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        @forelse($laporanMember as $index => $m)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $m['nama_member'] }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $m['kode_member'] }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    Rp {{ number_format($m['total_belanja']) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        + Rp {{ number_format($m['total_laba']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs px-3 py-1.5 rounded-md transition-colors border border-slate-300 focus:outline-none" 
                                            type="button" 
                                            onclick="toggleDetail('detailMember{{ $index }}')">
                                        Detail Belanja
                                    </button>
                                </td>
                            </tr>
                            
                            <tr class="hidden bg-slate-50/50" id="detailMember{{ $index }}">
                                <td colspan="4" class="px-8 py-4 border-t border-b border-slate-200/60">
                                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                                        <table class="w-full text-xs text-left">
                                            <thead>
                                                <tr class="bg-slate-100 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                                                    <th class="px-4 py-2.5">Nama Barang / Satuan</th>
                                                    <th class="px-4 py-2.5 text-center">Qty</th>
                                                    <th class="px-4 py-2.5">Subtotal Jual</th>
                                                    <th class="px-4 py-2.5 text-emerald-700">Laba Bersih Item</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 text-slate-600">
                                                @foreach($m['barang_dibeli'] as $barang)
                                                    <tr class="hover:bg-slate-50/50">
                                                        <td class="px-4 py-2.5 font-medium text-slate-700">{{ $barang['nama_produk'] }}</td>
                                                        <td class="px-4 py-2.5 text-center font-bold text-slate-800">{{ $barang['jumlah'] }}</td>
                                                        <td class="px-4 py-2.5">Rp {{ number_format($barang['subtotal']) }}</td>
                                                        <td class="px-4 py-2.5 text-emerald-600 font-semibold">Rp {{ number_format($barang['laba']) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">
                                    Tidak ada aktivitas transaksi dari member pada periode bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script>
    function toggleDetail(id) {
        const targetRow = document.getElementById(id);
        if (targetRow) {
            // Menghapus atau menambah class 'hidden' untuk memunculkan/menyembunyikan baris
            targetRow.classList.toggle('hidden');
        }
    }
</script>
@endsection