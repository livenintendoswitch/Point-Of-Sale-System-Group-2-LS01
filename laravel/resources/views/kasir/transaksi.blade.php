@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-2 mx-auto">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 items-start">
        
        <div class="xl:col-span-2 space-y-5 w-full">
            
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    Scan / Cari Produk
                </h2>
                
                <form action="{{ route('scan') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Barcode / Scan</label>
                            <input type="text" name="barcode" id="barcode" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 text-sm bg-amber-50 font-mono" 
                                placeholder="Scan / ketik di sini..." required autofocus>
                        </div>

                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Barang</label>
                            <input type="text" id="nama_produk_terdeteksi" 
                                class="w-full border-gray-200 rounded-lg shadow-sm bg-gray-100 py-2 px-3 text-sm font-bold text-gray-700" 
                                placeholder="Menunggu scan..." readonly>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Satuan/Kemasan</label>
                            <select name="kemasan_id" id="kemasan_id" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 text-sm" required>
                                <option value="">-- Scan Barcode Dulu --</option>
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Qty</label>
                            <input type="number" name="qty" id="qty_input" min="1" value="1" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 text-center text-sm font-bold" required>
                        </div>

                        <div class="md:col-span-1">
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 font-bold shadow transition-all active:scale-95 text-sm flex items-center justify-center">
                                +
                            </button>
                        </div>
                        
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-800">Keranjang Belanja</h2>
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">{{ count($cart) }} Item Terdaftar</span>
                </div>
                
                <div class="overflow-x-auto w-full">
                    <table class="w-full min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                                <th class="px-5 py-3 text-left font-bold">Produk</th>
                                <th class="px-5 py-3 text-right font-bold">Harga Satuan</th>
                                <th class="px-5 py-3 text-center font-bold">Qty</th>
                                <th class="px-5 py-3 text-right font-bold">Subtotal</th>
                                <th class="px-5 py-3 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse($cart as $id => $item)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3">
                                    <div class="font-bold text-gray-800">{{ $item['nama'] }}</div>
                                </td>
                                <td class="px-5 py-3 text-right text-gray-600">
                                    Rp {{ number_format($item['harga_satuan']) }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <input type="number" value="{{ $item['qty'] }}" 
                                        class="qty-input w-16 border-gray-300 rounded-md text-center focus:ring-indigo-500 focus:border-indigo-500 text-sm py-1" 
                                        data-id="{{ $id }}">
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-indigo-600">
                                    Rp {{ number_format($item['subtotal']) }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ route('removeCart', $id) }}" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')" 
                                    class="text-gray-400 hover:text-red-500 transition-colors inline-block">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-400 italic bg-white">
                                    Keranjang belanja kosong. Silakan scan atau masukkan produk terlebih dahulu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="bg-slate-900 text-white p-5 rounded-xl shadow-md mb-4 text-right">
                <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Total Belanja</div>
                <div class="text-3xl font-black tracking-tight text-emerald-400">Rp {{ number_format($total) }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center pb-2 border-b border-gray-100">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Penyelesaian Transaksi
                </h2>
                
                <form action="{{ route('prosesBayar') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Scan / Kode Member (Opsional)</label>
                        <input type="text" id="kode_member" name="kode_member" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" 
                            placeholder="Masukkan ID member jika ada">
                        <input type="hidden" name="member_id" id="member_id">
                        <div id="member_info" class="mt-2 text-xs text-emerald-600 font-bold hidden"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Uang Tunai Diterima (Bayar)</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 font-bold text-sm">Rp</span>
                            </div>
                            <input type="number" name="dibayar" id="dibayar"
                                class="w-full border-gray-300 rounded-lg pl-9 pr-3 py-2.5 text-xl font-bold text-emerald-700 focus:ring-emerald-500 focus:border-emerald-500" 
                                required placeholder="0">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white py-3.5 rounded-xl hover:bg-emerald-700 font-black text-base shadow-md hover:shadow-lg transition-all active:scale-95 flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        PROSES TRANSAKSI (BAYAR)
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</div>

<script>
    // Pencarian Member via AJAX
    document.getElementById('kode_member').addEventListener('blur', function() {
        let kode = this.value;
        if(!kode) return;
        fetch(`/transaksi/cari-member?kode=${kode}`)
            .then(res => res.json())
            .then(data => {
                const info = document.getElementById('member_info');
                if (data && data.id) {
                    document.getElementById('member_id').value = data.id;
                    info.textContent = '✅ Member Terverifikasi: ' + data.nama;
                    info.classList.remove('hidden');
                } else {
                    document.getElementById('member_id').value = '';
                    info.textContent = '❌ Member tidak ditemukan';
                    info.classList.remove('hidden');
                }
            });
    });

    // Update kuantitas barang lewat AJAX
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

    // Auto Focus Input Barcode saat Halaman Selesai Dimuat
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('barcode').focus();
    });

    document.getElementById('barcode').addEventListener('input', function() {
        let searchValue = this.value;
        let namaInput = document.getElementById('nama_produk_terdeteksi');
        let kemasanSelect = document.getElementById('kemasan_id');

        // Jika inputan dihapus sampai kosong, kembalikan ke kondisi awal
        if (searchValue.length < 2) {
            namaInput.value = '';
            kemasanSelect.innerHTML = '<option value="">-- Scan Barcode Dulu --</option>';
            return;
        }

        // Lakukan pencarian ke server secara mandiri (AJAX) tanpa reload halaman
        fetch(`/transaksi/cari-produk-kasir?search=${encodeURIComponent(searchValue)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan nama barang yang sukses terdeteksi
                    namaInput.value = data.produk.nama;

                    // Kosongkan isi dropdown kemasan lama, lalu isi dengan kemasan produk ini saja
                    kemasanSelect.innerHTML = '';
                    
                    data.kemasan.forEach((k, index) => {
                        let option = document.createElement('option');
                        option.value = k.id;
                        // Format tampilan dropdown: "Besar (Isi 10) - Rp 150.000"
                        option.text = `${k.nama} (${k.satuan}) - Rp ${new Intl.NumberFormat('id-ID').format(k.harga_jual)}`;
                        kemasanSelect.appendChild(option);
                    });
                } else {
                    // Jika tidak ketemu/salah ketik
                    namaInput.value = '❌ Produk Tidak Terdaftar';
                    kemasanSelect.innerHTML = '<option value="">-- Tidak Ada Satuan --</option>';
                }
            }).catch(err => console.error("Error pencarian produk:", err));
    });

    // AUTO PRINT JIKALAU TRANSAKSI AMAN
    @if(session()->has('print_penjualan_id'))
        let urlCetak = "{{ route('cetak_struk', session('print_penjualan_id')) }}";
        let lunasPrint = window.open(urlCetak, '_blank', 'width=600,height=700,scrollbars=yes,menubar=no,status=no,toolbar=no');
        if (lunasPrint) {
            lunasPrint.focus();
        }
    @endif
</script>
@endsection