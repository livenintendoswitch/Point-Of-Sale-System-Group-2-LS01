@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Data Inventori Produk</h1>
    <a href="{{ route('produk.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">+ Produk Baru</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-3">Barcode / Nama</th>
                <th class="p-3">Stok Total</th>
                <th class="p-3 text-blue-700">Kemasan Besar</th>
                <th class="p-3 text-green-700">Kemasan Sedang</th>
                <th class="p-3 text-yellow-700">Kemasan Kecil</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $p)
            @php
                // Mapping kemasan untuk memudahkan display
                $besar = $p->kemasan->where('nama', 'Besar')->first();
                $sedang = $p->kemasan->where('nama', 'Sedang')->first();
                $kecil = $p->kemasan->where('nama', 'Kecil')->first();
            @endphp
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">
                    <span class="text-xs text-gray-500 block">{{ $p->barcode }}</span>
                    <span class="font-bold">{{ $p->nama }}</span>
                </td>
                <td class="p-3">
                    <span class="bg-gray-800 text-white px-2 py-1 rounded text-sm">{{ $p->stok }} PCS</span>
                </td>
                
                <!-- Display Harga Jual per Level -->
                <td class="p-3">
                    @if($besar)
                        <div class="text-xs">{{ $besar->satuan }} (isi {{ $besar->konversi }})</div>
                        <div class="font-semibold">Rp {{ number_format($besar->harga_jual) }}</div>
                    @endif
                </td>
                <td class="p-3">
                    @if($sedang)
                        <div class="text-xs">{{ $sedang->satuan }} (isi {{ $sedang->konversi }})</div>
                        <div class="font-semibold">Rp {{ number_format($sedang->harga_jual) }}</div>
                    @endif
                </td>
                <td class="p-3">
                    @if($kecil)
                        <div class="text-xs">{{ $kecil->satuan }}</div>
                        <div class="font-semibold">Rp {{ number_format($kecil->harga_jual) }}</div>
                    @endif
                </td>

                <td class="p-3 text-center">
                    <div class="flex justify-center space-x-2">
                        <a href="{{ route('produk.edit', $p->id) }}" class="bg-yellow-400 text-white p-1 rounded hover:bg-yellow-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        <form action="{{ route('produk.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-1 rounded hover:bg-red-600" onclick="return confirm('Hapus produk ini?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">
    {{ $produks->links() }}
</div>
@endsection