@extends('layouts.app')
@section('content')
<div class="grid grid-cols-3 gap-4">
    <div class="col-span-2">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Scan Produk</h2>
            <form action="{{ route('scan') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="barcode" placeholder="Scan Barcode" class="flex-1 border rounded px-3 py-2" autofocus>
                <select name="kemasan_id" class="border rounded px-3 py-2" required>
                    <option value="">Pilih Kemasan</option>
                    @foreach($daftarKemasan as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->konversi }} pcs) - Rp {{ number_format($k->harga_jual) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
            </form>
        </div>
        <div class="bg-white p-4 rounded shadow mt-4">
            <h2 class="text-xl font-bold mb-4">Keranjang</h2>
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ number_format($item['harga']) }}</td>
                        <td><input type="number" value="{{ $item['qty'] }}" class="qty-input w-16 border" data-id="{{ $id }}"></td>
                        <td>{{ number_format($item['subtotal']) }}</td>
                        <td><a href="{{ route('removeCart', $id) }}" class="text-red-600">Hapus</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 text-right font-bold">Total: Rp {{ number_format($total) }}</div>
        </div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Pembayaran</h2>
        <form action="{{ route('prosesBayar') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Kode Member (opsional)</label>
                <input type="text" id="kode_member" name="kode_member" class="w-full border rounded px-3 py-2" placeholder="Scan member">
                <input type="hidden" name="member_id" id="member_id">
            </div>
            <div class="mb-3">
                <label>Total Bayar</label>
                <input type="number" name="dibayar" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Bayar</button>
        </form>
    </div>
</div>
<script>
    document.getElementById('kode_member').addEventListener('blur', function() {
        let kode = this.value;
        fetch(`/transaksi/cari-member?kode=${kode}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.id) {
                    document.getElementById('member_id').value = data.id;
                    alert('Member: ' + data.nama);
                } else {
                    document.getElementById('member_id').value = '';
                }
            });
    });
    // Update qty via ajax
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let id = this.dataset.id;
            let qty = this.value;
            fetch('{{ route("updateCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: id,
                    qty: qty
                })
            }).then(() => location.reload());
        });
    });
</script>
@endsection