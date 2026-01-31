<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background: #f2f2f2;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h3 class="mb-3">
    Laporan Pengeluaran internal <br> 
    {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Deskripsi</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengeluaran as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->deskripsi }}</td>
            <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"><b>Total</b></td>
            <td><b>Rp {{ number_format($total,0,',','.') }}</b></td>
        </tr>
    </tbody>
</table>

</body>
</html>
