@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Member</h1>

    <form method="POST" action="{{ route('member.update', $member->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Kode Member</label>
            <input type="text" name="kode_member" value="{{ old('kode_member', $member->kode_member) }}" class="w-full border rounded px-3 py-2" required>
            @error('kode_member') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama', $member->nama) }}" class="w-full border rounded px-3 py-2" required>
            @error('nama') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon', $member->telepon) }}" class="w-full border rounded px-3 py-2">
            @error('telepon') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Poin</label>
            <input type="number" name="poin" value="{{ old('poin', $member->poin) }}" class="w-full border rounded px-3 py-2">
            @error('poin') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('member.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>
@endsection