@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold">Data Member</h1>
    <a href="{{ route('member.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">+ Tambah Member</a>
</div>

<div class="mt-6 bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Member</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($members as $member)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $member->kode_member }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $member->nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $member->telepon ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($member->poin) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('member.edit', $member->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                    <form action="{{ route('member.destroy', $member->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus member {{ $member->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data member.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $members->links() }}
</div>
@endsection