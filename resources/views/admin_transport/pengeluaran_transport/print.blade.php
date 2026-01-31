<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran Transport</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header p {
            margin: 4px 0 0 0;
            font-size: 12px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* spacing antar kelompok unit */
        .group-total td {
            background: #fafafa;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- JUDUL -->
    <div class="header mb-3">
        <h3>LAPORAN PENGELUARAN TRANSPORT <br>
         {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</h3>
      
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Plat Nomor</th>
                <th width="15%">Tanggal</th>
                <th>Keterangan</th>
                <th width="15%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transport as $index => $t)
                @foreach($t->items as $itemIndex => $item)
                <tr>
                    @if($itemIndex === 0)
                        <td rowspan="{{ $t->items->count() }}" class="text-center">
                            {{ $index + 1 }}
                        </td>
                        <td rowspan="{{ $t->items->count() }}">
                            {{ $t->unit->nama_unit }}
                        </td>
                        <td rowspan="{{ $t->items->count() }}" class="text-center">
                            {{ $t->tanggal }}
                        </td>
                    @endif
                    <td>{{ $item->keterangan }}</td>
                    <td class="text-right">
                        {{ number_format($item->nominal,0,',','.') }}
                    </td>
                </tr>
                @endforeach

                <!-- TOTAL PER UNIT -->
                <tr class="group-total">
                    <td colspan="4" class="fw-bold">
                        Total {{ $t->unit->nama_unit }}
                    </td>
                    <td class="fw-bold text-right">
                        {{ number_format($t->total_amount,0,',','.') }}
                    </td>
                </tr>
            @endforeach

            <!-- TOTAL KESELURUHAN -->
            <tr>
                <td colspan="4" class="fw-bold">
                    Total Keseluruhan
                </td>
                <td class="fw-bold text-right">
                    {{ number_format($total_all,0,',','.') }}
                </td>
            </tr>
        </tbody>
    </table>

</div>

</body>
</html>
