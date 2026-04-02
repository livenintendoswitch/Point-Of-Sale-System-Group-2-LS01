@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold">Dashboard Admin</h1>
<div class="grid grid-cols-4 gap-4 mt-6">
    <a href="{{ route('produk.index') }}" class="bg-white p-4 rounded shadow text-center">Kelola Produk</a>
    <a href="{{ route('member.index') }}" class="bg-white p-4 rounded shadow text-center">Kelola Member</a>
    <a href="{{ route('user.index') }}" class="bg-white p-4 rounded shadow text-center">Kelola User</a>
    <a href="{{ route('laporan') }}" class="bg-white p-4 rounded shadow text-center">Lihat Laporan</a>
</div>
@endsection