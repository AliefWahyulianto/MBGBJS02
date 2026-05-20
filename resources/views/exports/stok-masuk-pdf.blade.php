<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Stok Masuk</h1>
        <p>Dapur MBG - Bojongsari 02</p>
        <p>Periode: {{ $startDate ?: 'Semua' }} - {{ $endDate ?: 'Semua' }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Bahan</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokMasuk as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                <td>{{ $item->bahan->nama ?? '-' }}</td>
                <td>{{ number_format($item->jumlah, 2) }} {{ $item->bahan->satuan ?? '' }}</td>
                <td>{{ $item->catatan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>