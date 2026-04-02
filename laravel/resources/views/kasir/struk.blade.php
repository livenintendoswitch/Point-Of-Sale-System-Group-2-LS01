<!DOCTYPE html>
<html>

<head>
    <title>Struk Belanja</title>
    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed black;
            margin: 10px 0;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="text-center">
        <h3>TOKO POS</h3>
        <p>{{ $penjualan->created_at->format('d/m/Y H:i:s') }}<br>Kasir: {{ $penjualan->user->name }}</p>
    </div>
    <div class="line"></div>
    <table width="100%">
        @foreach($penjualan->details as $detail)
        <tr>
            <td>{{ $detail->produk->nama }} x{{ $detail->jumlah }}</td>
            <td align="right">{{ number_format($detail->subtotal) }}</td>
        </tr>
        @endforeach
    </table>
    <div class="line"></div>
    <table width="100%">
        <tr>
            <td>Total</td>
            <td align="right">{{ number_format($penjualan->total_harga) }}</td>
        </tr>
        <tr>
            <td>Dibayar</td>
            <td align="right">{{ number_format($penjualan->dibayar) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td align="right">{{ number_format($penjualan->kembalian) }}</td>
        </tr>
    </table>
    <div class="line"></div>
    <p class="text-center">Terima kasih telah berbelanja</p>
    <script>
        setTimeout(() => window.close(), 2000);
    </script>
</body>

</html>