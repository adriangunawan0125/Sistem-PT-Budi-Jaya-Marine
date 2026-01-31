<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pajak Kendaraan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px; /* ⬅️ JARAK HEADER KE TABEL */
        }

        .header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* ⬅️ JARAK TAMBAHAN BIAR AMAN */
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h3>
        LAPORAN PAJAK KENDARAAN <br>
        {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
    </h3>
</div>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Unit</th>
            <th width="15%">Tanggal</th>
            <th>Deskripsi</th>
            <th width="15%">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pajak as $i => $p)
        <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $p->unit->nama_unit }}</td>
            <td class="text-center">{{ $p->tanggal }}</td>
            <td>{{ $p->deskripsi }}</td>
            <td class="text-right">
                {{ number_format($p->nominal,0,',','.') }}
            </td>
        </tr>
        @endforeach

        <tr>
            <td colspan="4" class="fw-bold">Total</td>
            <td class="fw-bold text-right">
                {{ number_format($total,0,',','.') }}
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
