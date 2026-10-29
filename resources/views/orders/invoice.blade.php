<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->invoice_number }} - Savonia.id</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 30px;
            background: #f8fafc;
            font-size: 14px;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-area img {
            height: 48px;
            border-radius: 50%;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }
        .brand-name span {
            color: #FF6600;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
            font-weight: 800;
        }
        .grid-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .info-box h4 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
        }
        .info-box p {
            margin: 0;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #cbd5e1;
            font-size: 13px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 320px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .totals-table td {
            padding: 8px 12px;
        }
        .grand-total {
            font-size: 18px;
            font-weight: 800;
            color: #FF6600;
            border-top: 2px solid #e2e8f0;
        }
        .print-btn-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            background: #FF6600;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 9999px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-card {
                border: none;
                box-shadow: none;
                padding: 0;
            }
            .print-btn-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button class="btn" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="invoice-card">
        <!-- Header -->
        <div class="header">
            <div class="logo-area">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                <div>
                    <div class="brand-name">SAVONIA<span>.ID</span></div>
                    <div style="font-size: 12px; color: #64748b;">Toko Aksesoris Teknologi & Gadget Terlengkap</div>
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE PENJUALAN</h2>
                <div style="color: #64748b; font-size: 13px; margin-top: 4px;">{{ $order->invoice_number }}</div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid-info">
            <div class="info-box">
                <h4>Ditujukan Kepada:</h4>
                <p>
                    <strong>{{ $order->recipient_name }}</strong><br>
                    No. HP: {{ $order->recipient_phone }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }} {{ $order->shipping_postal_code }}
                </p>
            </div>
            <div class="info-box" style="text-align: right;">
                <h4>Informasi Transaksi:</h4>
                <p>
                    <strong>Tanggal:</strong> {{ $order->created_at->translatedFormat('d F Y') }}<br>
                    <strong>Status Bayar:</strong> {{ strtoupper($order->payment_status) }}<br>
                    <strong>Metode Pembayaran:</strong> Midtrans Sandbox ({{ strtoupper($order->payment_type ?? 'Online') }})<br>
                    <strong>Ekspedisi Kurir:</strong> {{ $order->shipping_courier }}<br>
                    @if($order->tracking_number)
                        <strong>No. Resi:</strong> {{ $order->tracking_number }}<br>
                    @endif
                </p>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 45%;">Nama Produk Aksesoris</th>
                    <th style="width: 20%;" class="text-right">Harga Satuan</th>
                    <th style="width: 10%;" class="text-right">Jumlah</th>
                    <th style="width: 20%;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product_sku)
                                <div style="font-size: 11px; color: #64748b;">SKU: {{ $item->product_sku }}</div>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Table -->
        <table class="totals-table">
            <tr>
                <td>Subtotal Produk:</td>
                <td class="text-right"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Ongkos Kirim ({{ $order->shipping_courier }}):</td>
                <td class="text-right"><strong>{{ $order->shipping_cost == 0 ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="grand-total">
                <td>Total Tagihan:</td>
                <td class="text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Footer / Signature -->
        <div style="display: flex; justify-content: space-between; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;">
            <div>
                <strong>SAVONIA.ID Official Store</strong><br>
                Punge Ujong, Kec. Meuraxa, Kota Banda Aceh, Aceh<br>
                WhatsApp: 0812-3456-7890 | Website: savonia.id
            </div>
            <div style="text-align: right;">
                <p>Status: <strong>{{ $order->isPaid() ? 'LUNAS (PAID)' : 'MENUNGGU PEMBAYARAN' }}</strong></p>
                <div style="margin-top: 40px; font-weight: 700; color: #0f172a;">( Admin Savonia.id )</div>
            </div>
        </div>
    </div>

</body>
</html>
