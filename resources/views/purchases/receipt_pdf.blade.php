<style>
    .container {
        max-width: 720px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        font-family: Arial, sans-serif;
        color: #333;
    }

    .header,
    .section {
        margin-bottom: 1.5rem;
    }

    .header-top {
        text-align: right;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #555;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #444;
    }

    .info-list p {
        margin: 0.25rem 0;
        font-size: 0.9rem;
        color: #555;
    }

    .info-list span {
        font-weight: bold;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 0.5rem;
        text-align: left;
    }

    th {
        background-color: #f5f5f5;
    }

    .total-row {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .text-muted {
        color: #666;
    }

    .thank-you {
        text-align: center;
        margin-top: 2rem;
        font-size: 1rem;
        font-weight: bold;
        color: #444;
    }
</style>

<div class="container">

    <div class="header">
        <div class="header-top">
            <p><strong>Nomor Transaksi:</strong> #{{ $purchase->id }}</p>
            <p>{{ $purchase->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
        </div>
    </div>

    @if ($purchase->member)
    <div class="section">
        <div class="section-title">Detail Member</div>
        <div class="info-list">
            <p><span>Nama:</span> {{ $purchase->member->name }}</p>
            <p><span>No. Telepon:</span> {{ $purchase->member->no_phone }}</p>
            <p class="text-sm text-gray-600">
                Tanggal Bergabung: {{ \Carbon\Carbon::parse($purchase->member->created_at)->translatedFormat('d F Y') }}
            </p>
            <p class="text-sm text-gray-600">Poin yang Digunakan: Rp {{ number_format($purchase->diskon_poin, 0, ',', '.') }}
            </p>
            <p><span>Poin yang Tersisa:</span> {{ number_format($purchase->member->poin, 0, ',', '.') }}</p>
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Rincian Produk</div>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->products as $item)
                <tr>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->pivot->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total Pembelian</td>
                    <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Rincian Pembayaran</div>
        <table>
            <tbody>
                <tr>
                    <td>Total Bayar</td>
                    <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pembayaran</td>
                    <td>Rp {{ number_format($purchase->total_bayar, 0, ',', '.') }}</td>
                </tr>
                @if (session('change') > 0)
                <tr class="total-row">
                    <td>Kembalian</td>
                    <td class="text-muted">Rp {{ number_format(session('change'), 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="thank-you">
        <p>Terima kasih telah berbelanja!</p>
    </div>

	</div>
