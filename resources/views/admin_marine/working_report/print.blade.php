<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Working Report {{ $workingReport->id }}</title>

<style>
body{
    font-family: Arial, sans-serif;
    font-size:12px;
    margin:0;
    padding:0 40px;
}

table{
    width:100%;
    border-collapse:collapse;
}

.kop{
    border-bottom:1.5px solid #000;
    padding-bottom:10px;
    margin-bottom:15px;
}

.company-name{
    font-size:18px;
    font-weight:bold;
    text-align:center;
}

.center{text-align:center;}
.bold{font-weight:bold;}

.header-table td{
    border:1px solid #000;
    padding:6px 8px;
}

.section-title{
    text-align:center;
    font-weight:bold;
    margin:20px 0 10px 0;
}

.report-table td{
    border:1px solid #000;
    vertical-align:top;
}

.date-title{
    font-weight:bold;
    text-decoration:underline;
    margin-bottom:6px;
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
                Ruko Sentra Bisnis, Jl. Harapan Indah No.33, Blok SS-2<br>
                Pejuang, Medan Satria, Bekasi 17131<br>
                Email: sasongko@budijayamarine.com | cs@budijayamarine.com
            </td>
            <td width="20%"></td>
        </tr>
    </table>
</div>

{{-- ================= HEADER TABLE (PERSIS SEPERTI CONTOH) ================= --}}
<table class="header-table">

<tr>
    <td width="25%" class="bold">COMPANY NAME</td>
    <td width="75%">{{ $workingReport->poMasuk->mitra_marine }}</td>
</tr>

<tr>
    <td class="bold">PROJECT</td>
    <td>{{ $workingReport->project }}</td>
</tr>

<tr>
    <td class="bold">VESSEL NAME</td>
    <td>{{ $workingReport->poMasuk->vessel }}</td>
</tr>

<tr>
    <td class="bold">PLACE</td>
    <td>{{ $workingReport->place ?? '-' }}</td>
</tr>

<tr>
    <td class="bold">PO NO</td>
    <td>{{ $workingReport->poMasuk->no_po_klien }}</td>
</tr>

<tr>
    <td class="bold">MANPOWER</td>
    <td>{{ $workingReport->manpower ?? '-' }}</td>
</tr>

<tr>
    <td class="bold">PERIODE</td>
    <td>{{ $workingReport->periode }}</td>
</tr>

</table>

<div class="section-title">
    WORKING REPORT
</div>

{{-- ================= REPORT CONTENT ================= --}}
<table class="report-table">

@foreach($workingReport->items as $item)
<tr>

    {{-- LEFT SIDE --}}
    <td width="35%" style="padding:12px;">

        <div class="date-title">
            {{ \Carbon\Carbon::parse($item->work_date)->translatedFormat('l, d F Y') }}
        </div>

        <div style="white-space: pre-line;">
            {{ ltrim($item->detail) }}
        </div>

    </td>

    {{-- RIGHT SIDE IMAGES --}}
    <td width="65%" style="padding:8px;">

        @if($item->images->count() > 0)

            <table width="100%" style="border-collapse:collapse;">
                <tr>

                @foreach($item->images as $img)

                    <td width="50%" style="padding:5px; border:none;">
                        <img src="{{ public_path('storage/'.$img->image_path) }}"
                             style="width:100%; height:230px; object-fit:cover;">
                    </td>

                    @if($loop->iteration % 2 == 0)
                        </tr><tr>
                    @endif

                @endforeach

                </tr>
            </table>

        @endif

    </td>

</tr>
@endforeach

</table>

</body>
</html>
