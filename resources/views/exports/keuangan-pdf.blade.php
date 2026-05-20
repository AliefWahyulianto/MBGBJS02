<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; padding: 10px; background: #f5f5f5; }
        .summary div { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-masuk { color: green; font-weight: bold; }
        .total-keluar { color: red; font-weight: bold; }
        .saldo { font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Dapur MBG - Bojongsari 02</p>
        <p>Periode: {{ $startDate ?: 'Semua' }} - {{ $endDate ?: 'Semua' }}</p>
    </div>

    <div class="summary">
        <div>Total Pemasukan: <span class="total-masuk">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span></div>
        <div>Total Pengeluaran: <span class="total-keluar">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span></div>
        <div class="saldo">Saldo: Rp {{ number_format($saldo, 0, ',', '.') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->jenis == 'masuk' ? 'Masuk' : 'Keluar' }}</td>
                <td>{{ $item->kategori }}</td>
                <td class="{{ $item->jenis == 'masuk' ? 'total-masuk' : 'total-keluar' }}">
                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>