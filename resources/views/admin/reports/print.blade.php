<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Savonia.id - {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</title>
    <style>
        @media print {
            body { margin: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            background: white;
            line-height: 1.4;
        }

        .report-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm 15mm;
        }

        /* Header */
        .report-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 14px;
            border-bottom: 3px solid #FF6600;
            margin-bottom: 20px;
        }
        .report-header img {
            height: 55px;
            border-radius: 50%;
        }
        .report-header .company-info h1 {
            font-size: 18pt;
            font-weight: 800;
            color: #16192B;
        }
        .report-header .company-info p {
            font-size: 9pt;
            color: #666;
        }

        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h2 {
            font-size: 14pt;
            font-weight: 700;
            color: #16192B;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-title p {
            font-size: 10pt;
            color: #888;
            margin-top: 4px;
        }

        /* Summary */
        .summary-row {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .summary-box {
            flex: 1;
            border: 1.5px solid #E2E8F0;
            border-radius: 8px;
            padding: 12px 14px;
            text-align: center;
        }
        .summary-box .label {
            font-size: 8pt;
            color: #888;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .summary-box .value {
            font-size: 16pt;
            font-weight: 800;
            color: #16192B;
            margin-top: 4px;
        }
        .summary-box .value.accent {
            color: #FF6600;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead th {
            background: #16192B;
            color: white;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
            font-weight: 700;
        }
        thead th:first-child { border-radius: 6px 0 0 0; }
        thead th:last-child { border-radius: 0 6px 0 0; }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #E2E8F0;
            font-size: 9pt;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background: #F8FAFC;
        }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .text-orange { color: #FF6600; }
        .text-green { color: #047857; }
        .text-red { color: #DC2626; }

        /* Footer */
        .report-footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .sign-block {
            text-align: center;
            width: 200px;
        }
        .sign-block .date {
            font-size: 9pt;
            color: #666;
            margin-bottom: 60px;
        }
        .sign-block .name {
            font-weight: 700;
            border-top: 1px solid #333;
            padding-top: 6px;
            font-size: 10pt;
        }
        .sign-block .role {
            font-size: 8pt;
            color: #888;
        }

        /* Print button */
        .print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 12px 24px;
            background: #16192B;
            display: flex;
            justify-content: center;
            gap: 12px;
            z-index: 100;
        }
        .print-bar button, .print-bar a {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-family: inherit;
        }
        .btn-print { background: #FF6600; color: white; }
        .btn-back { background: white; color: #16192B; }
    </style>
</head>
<body>
    {{-- Print Bar --}}
    <div class="print-bar no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <a href="{{ route('admin.reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-back">← Kembali</a>
    </div>

    <div class="report-container" style="margin-top: 60px;">
        {{-- Header --}}
        <div class="report-header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Savonia.id">
            <div class="company-info">
                <h1>SAVONIA.ID</h1>
                <p>Toko Aksesoris Online — savonia.id</p>
                <p>Jl. Contoh Alamat No. 123, Kota, Indonesia</p>
            </div>
        </div>

        {{-- Title --}}
        <div class="report-title">
            <h2>Laporan Penjualan</h2>
            <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>

        {{-- Summary --}}
        <div class="summary-row">
            <div class="summary-box">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ $orders->count() }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Transaksi Lunas</div>
                <div class="value text-green">{{ $paidOrdersCount }}</div>
            </div>
            <div class="summary-box">
                <div class="label">Total Pendapatan</div>
                <div class="value accent">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Table --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th class="text-right">Total (Rp)</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-bold text-orange">{{ $order->invoice_number }}</td>
                    <td>{{ $order->user->name ?? $order->recipient_name }}</td>
                    <td>
                        @foreach($order->items->take(2) as $item)
                            {{ $item->product_name }} ({{ $item->quantity }}x){{ !$loop->last ? ', ' : '' }}
                        @endforeach
                        @if($order->items->count() > 2)
                            <span style="color: #999;">+{{ $order->items->count() - 2 }} lainnya</span>
                        @endif
                    </td>
                    <td class="text-right font-bold">{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td>
                        <span class="{{ $order->status == 'completed' ? 'text-green' : ($order->status == 'cancelled' ? 'text-red' : '') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="{{ $order->payment_status == 'paid' ? 'text-green' : 'text-red' }} font-bold">
                        {{ $order->payment_status == 'paid' ? 'Lunas' : ucfirst($order->payment_status) }}
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px; color: #999;">Tidak ada transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer / Signature --}}
        <div class="report-footer">
            <div style="font-size: 8pt; color: #999;">
                Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB<br>
                Sistem Informasi Penjualan Savonia.id — Tugas Akhir
            </div>
            <div class="sign-block">
                <div class="date">{{ now()->format('d M Y') }}</div>
                <div class="name">Administrator</div>
                <div class="role">Pengelola Toko Savonia.id</div>
            </div>
        </div>
    </div>
</body>
</html>
