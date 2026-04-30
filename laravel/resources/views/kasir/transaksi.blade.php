@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: SCAN & KERANJANG -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card Scan Produk -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Scan Produk
                </h2>
                <form action="{{ route('scan') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Barcode / Nama</label>
                            <input type="text" name="barcode" id="barcode" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5" 
                                placeholder="Ketik/Scan..." required autofocus>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pilih Kemasan</label>
                            <select name="kemasan_id" id="kemasan_id" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($daftarKemasan as $k)
                                    <option value="{{ $k->id }}">
                                        {{ $k->produk->nama }} ({{ $k->nama }}) - Rp {{ number_format($k->harga_jual) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jumlah (Qty)</label>
                            <input type="number" name="qty" id="qty_input" min="1" value="1" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 text-center" required>
                        </div>

                        <div class="md:col-span-1">
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg hover:bg-indigo-700 font-bold shadow-md transition-all active:scale-95">
                                + Keranjang
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Card Keranjang Belanja -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold">Keranjang Belanja</h2>
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ count($cart) }} Item</span>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 text-left font-semibold">Produk</th>
                                <th class="px-6 py-4 text-right font-semibold">Harga</th>
                                <th class="px-6 py-4 text-center font-semibold">Qty</th>
                                <th class="px-6 py-4 text-right font-semibold">Subtotal</th>
                                <th class="px-6 py-4 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($cart as $id => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $item['nama'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600">
                                    Rp {{ number_format($item['harga_satuan']) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" value="{{ $item['qty'] }}" 
                                        class="qty-input w-16 border-gray-200 rounded-md text-center focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                                        data-id="{{ $id }}">
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-indigo-600">
                                    Rp {{ number_format($item['subtotal']) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('removeCart', $id) }}" class="text-gray-400 hover:text-red-500 transition">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Keranjang masih kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Keranjang -->
                <div class="bg-gray-50 p-6 flex justify-end">
                    <div class="text-right">
                        <div class="text-sm text-gray-500 mb-1 font-semibold uppercase">Total Pembayaran</div>
                        <div class="text-3xl font-black text-gray-900">Rp {{ number_format($total) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PEMBAYARAN -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-md border border-indigo-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-12 -mt-12"></div>
                
                <h2 class="text-xl font-bold mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pembayaran
                </h2>
                
                <form action="{{ route('prosesBayar') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 text-left">Member (Opsional)</label>
                        <input type="text" id="kode_member" name="kode_member" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                            placeholder="Scan/Ketik Kode Member">
                        <input type="hidden" name="member_id" id="member_id">
                        <div id="member_info" class="mt-2 text-xs text-green-600 font-bold hidden"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 text-left">Jumlah Bayar (Tunai)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="dibayar" id="dibayar"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 pl-10 py-3 text-xl font-bold text-green-700" 
                                required placeholder="0">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-xl hover:bg-green-700 font-black text-lg shadow-lg hover:shadow-xl transition-all active:scale-95 flex justify-center items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        PROSES BAYAR
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT TETAP SAMA NAMUN DENGAN PENYEMPURNAAN -->
<script>
    // Pencarian Member
    document.getElementById('kode_member').addEventListener('blur', function() {
        let kode = this.value;
        if(!kode) return;
        fetch(`/transaksi/cari-member?kode=${kode}`)
            .then(res => res.json())
            .then(data => {
                const info = document.getElementById('member_info');
                if (data && data.id) {
                    document.getElementById('member_id').value = data.id;
                    info.textContent = '✅ Member Terdeteksi: ' + data.nama;
                    info.classList.remove('hidden');
                } else {
                    document.getElementById('member_id').value = '';
                    info.textContent = '❌ Member tidak ditemukan';
                    info.classList.remove('hidden');
                }
            });
    });

    // Update qty via ajax
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let id = this.dataset.id;
            let qty = this.value;
            if(qty < 1) return;
            fetch('{{ route("updateCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, qty: qty })
            }).then(() => location.reload());
        });
    });

    // Auto Focus
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('barcode').focus();
    });
</script>
@endsection