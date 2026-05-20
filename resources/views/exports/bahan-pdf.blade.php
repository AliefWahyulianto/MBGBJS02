<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bahan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
        .status-aman { color: green; }
        .status-menipis { color: orange; }
        .status-habis { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Bahan Baku</h1>
        <p>Dapur MBG - Bojongsari 02</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bahans as $index => $bahan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $bahan->nama }}</td>
                <td>{{ $bahan->kategori }}</td>
                <td>{{ number_format($bahan->stok, 2) }}</td>
                <td>{{ $bahan->satuan }}</td>
                <td>Rp {{ number_format($bahan->harga_beli, 0, ',', '.') }}</td>
                <td class="status-{{ $bahan->status['text'] == 'Aman' ? 'aman' : ($bahan->status['text'] == 'Menipis' ? 'menipis' : 'habis') }}">
                    {{ $bahan->status['text'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem Informasi Manajemen Dapur MBG</p>
    </div>
</body>
</html>