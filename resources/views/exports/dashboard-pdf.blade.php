<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dashboard - {{ $dapurName }}</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }
        body {
            font-size: 12px;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #10b981;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #006c49;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
            color: #64748b;
        }
        .date {
            text-align: right;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 15px;
        }
        h2 {
            font-size: 14px;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background-color: #f8fafc;
        }
        .stat-card .label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
        }
        .stat-card .value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }
        .positive {
            color: #10b981;
        }
        .negative {
            color: #ef4444;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>{{ $dapurName }}</h1>
    <p>{{ $dapurAddress }}</p>
    <p>Laporan Dashboard Operasional</p>
</div>

<div class="date">
    Periode: {{ $tahun }} | Cetak: {{ date('d/m/Y H:i:s') }}
</div>

<!-- STATISTIK UTAMA -->
<h2>📊 Ringkasan Utama</h2>
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Total Bahan</div>
        <div class="value">{{ $totalBahan }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Stok Menipis</div>
        <div class="value negative">{{ $stokMenipis }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Stok Habis</div>
        <div class="value negative">{{ $stokHabis }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Pemasukan {{ $tahun }}</div>
        <div class="value positive">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Pengeluaran {{ $tahun }}</div>
        <div class="value negative">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Saldo</div>
        <div class="value {{ $saldo >= 0 ? 'positive' : 'negative' }}">
            Rp {{ number_format($saldo, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- GRAFIK PENGELUARAN (Tabel) -->
<h2>📈 Grafik Pengeluaran {{ $tahun }}</h2>
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th class="text-right">Pengeluaran (Rp)</th>
            <th class="text-center">Persentase</th>
        </thead>
    </thead>
    <tbody>
        @php
            $maxExpense = max(array_column($monthlyExpenses, 'amount')) ?: 1;
        @endphp
        @foreach($monthlyExpenses as $item)
        <tr>
            <td>{{ $item['month'] }}</td>
            <td class="text-right">Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
            <td class="text-center">
                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; width: 100%;">
                    <div style="background: #10b981; border-radius: 10px; height: 8px; width: {{ ($item['amount'] / $maxExpense) * 100 }}%;"></div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- TOP 5 BAHAN TERPAKAI -->
<h2>🏆 Top 5 Bahan Terpakai {{ $tahun }}</h2>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Bahan</th>
            <th class="text-right">Total Terpakai</th>
            <th class="text-center">Persentase</th>
        </thead>
    </thead>
    <tbody>
        @forelse($topBahan as $index => $item)
            @php
                $max = $topBahan->first()->total ?? 1;
                $persen = ($item->total / $max) * 100;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</td>
                <td class="text-right">{{ number_format($item->total, 2) }} {{ $item->bahan->satuan ?? '' }}</td>
                <td class="text-center">
                    <div style="background: #e2e8f0; border-radius: 10px; height: 8px; width: 100%;">
                        <div style="background: #3b82f6; border-radius: 10px; height: 8px; width: {{ $persen }}%;"></div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- KETERANGAN -->
<div class="footer">
    <p>Dokumen ini dicetak secara otomatis dari sistem KMS Admin Dapur MBG</p>
    <p>&copy; {{ date('Y') }} {{ $dapurName }} - All Rights Reserved</p>
</div>

</body>
</html>