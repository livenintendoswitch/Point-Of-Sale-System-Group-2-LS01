@extends('layouts.app')
@section('content')
<div class="flex justify-between">
    <h1 class="text-xl font-bold">Data Produk</h1>
    <a href="{{ route('produk.create') }}" class="bg-green-600 text-white px-3 py-1 rounded">+ Tambah</a>
</div>
<table class="w-full mt-4 bg-white rounded shadow">
    <thead>
        <tr>
            <th>Barcode</th>
            <th>Nama</th>
            <th>Stok (PCS)</th>
            <th>Kemasan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produks as $p)
        <tr>
            <td>{{ $p->barcode }}</td>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->stok }}</td>
            <td>
                @foreach($p->kemasan as $k)
                {{ $k->nama }} ({{ $k->konversi }} pcs) : Rp {{ number_format($k->harga_jual) }}<br>
                @endforeach
            </td>
            <td>
                <a href="{{ route('produk.edit', $p->id) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('produk.destroy', $p->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 ml-2" onclick="return confirm('Yakin?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $produks->links() }}
@endsection