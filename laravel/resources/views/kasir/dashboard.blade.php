@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold">Dashboard Kasir</h1>
<div class="mt-4">
    <a href="{{ route('transaksi') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Mulai Transaksi</a>
</div>
@endsection