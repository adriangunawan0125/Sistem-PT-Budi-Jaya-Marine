<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Timesheet {{ $timesheet->id }}</title>

<style>
body{
    font-family: Arial, sans-serif;
    font-size:12px;
    color:#000;
    margin:0;
    padding:0 40px;
}

table{
    width:100%;
    border-collapse:collapse;
}

.center{text-align:center;}
.bold{font-weight:bold;}

.kop{
    border-bottom:2px solid #000;
    padding-bottom:10px;
    margin-bottom:10px;
}

.company-name{
    font-size:18px;
    font-weight:bold;
    text-align:center;
}

.header-table td{
    padding:2px 0;
    vertical-align:top;
}

.item-table th,
.item-table td{
    border:1px solid #000;
    padding:5px;
    font-size:11px;
    vertical-align:top;
}

.item-table th{
    background:#114e60;
    color:#fff;
    text-align:center;
}

.signature{
    margin-top:60px;
}
</style>
</head>

<body>

{{-- ================= KOP ================= --}}
<div class="kop">
    <table>
        <tr>
            <td width="20%">
                <img src="{{ public_path('assets/kopbjm.jpeg') }}" width="90">
            </td>
            <td width="60%" class="center">
                <div class="company-name">PT. BUDI JAYA MARINE</div>
                Ruko Sentra Bisnis, Jl. Harapan Indah No.3 Blok SS 2<br>
                Pejuang, Medan Satria, Bekasi 17131<br>
                Email: sasongko@budijayamarine.com; cs@budijayamarine.com<br>
                Mobile: 087770239693
            </td>
            <td width="20%"></td>
        </tr>
    </table>
</div>

<h3 class="center" style="margin:10px 0;">TIME SHEET</h3>
{{-- ================= HEADER INFO ================= --}}
<table style="width:100%; margin-top:10px; margin-bottom:10px;">
<tr>

    {{-- LEFT BLOCK --}}
    <td width="50%" valign="top">
        <table style="width:100%;">
            <tr>
                <td width="130">Company Name</td>
                <td width="10" class="center">:</td>
                <td>{{ $timesheet->poMasuk->mitra_marine }}</td>
            </tr>
            <tr>
                <td>Project</td>
                <td class="center">:</td>
                <td>{{ $timesheet->project }}</td>
            </tr>
            <tr>
                <td>Vessel</td>
                <td class="center">:</td>
                <td>{{ $timesheet->poMasuk->vessel }}</td>
            </tr>
        </table>
    </td>

    {{-- RIGHT BLOCK --}}
    <td width="50%" valign="top" style="padding-left:40px;">
        <table style="width:100%;">
            <tr>
                <td width="110">Place</td>
                <td width="10" class="center">:</td>
                <td>{{ $timesheet->place ?? '-' }}</td>
            </tr>
            <tr>
                <td>PO No.</td>
                <td class="center">:</td>
                <td>{{ $timesheet->poMasuk->no_po_klien }}</td>
            </tr>
            <tr>
                <td>Manpower</td>
                <td class="center">:</td>
                <td>{{ $timesheet->manpower }}</td>
            </tr>
        </table>
    </td>

</tr>
</table>



{{-- ================= TABLE ================= --}}
<table class="item-table">
<thead>
<tr>
    <th width="4%">No</th>
    <th width="10%">Date</th>
    <th width="7%">Day</th>
    <th width="12%">Time</th>
    <th width="7%">Hours</th>
    <th width="8%">Manpower</th>
    <th>Kind Of Work</th>
</tr>
</thead>

<tbody>
@foreach($timesheet->items as $index => $item)
<tr>
    <td class="center">{{ $index+1 }}</td>

    <td class="center">
        {{ \Carbon\Carbon::parse($item->work_date)->format('n/j/Y') }}
    </td>

    <td class="center">
        {{ \Carbon\Carbon::parse($item->work_date)->format('l') }}
    </td>

    <td class="center">
        {{ substr($item->time_start,0,5) }} - {{ substr($item->time_end,0,5) }}
    </td>

    <td class="center">
        {{ number_format($item->hours,2) }}
    </td>

    <td class="center">
        {{ $item->manpower }}
    </td>

    <td>
        {!! nl2br(e(trim($item->kind_of_work))) !!}
    </td>
</tr>
@endforeach
</tbody>
</table>

<div class="signature">
    <table>
        <tr>
            <td width="50%" class="center">
                <br><br><br>
                (Chief Officer)
            </td>
            <td width="50%" class="center">
                <br><br><br>
                (Master)
            </td>
        </tr>
    </table>
</div>

</body>
</html>
