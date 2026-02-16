<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Delivery Order</title>

<style>
body{
    font-family:"Times New Roman", serif;
    font-size:13px;
    color:#000;
    margin:0;
    padding:0 40px; /* biar ada margin kiri kanan konsisten */
}

table{ width:100%; border-collapse:collapse; }

.center{ text-align:center; }
.right{ text-align:right; }
.bold{ font-weight:bold; }

/* ================= HEADER ================= */
.kop{
    border-bottom:1px solid #000;
    padding-bottom:8px;
    margin-bottom:10px;
    position:relative;
    text-align:center;
}

.logo{
    position:absolute;
    left:0;
    top:15;
}

.logo img{
    width:90px;
}

.company{
    font-size:13px;
    line-height:1.5;
}

.company-name{
    font-size:22px;
    font-weight:bold;
}

.do-title{
    text-align:center;
    font-weight:bold;
    text-decoration:underline;
    margin:8px 0 14px 0;
}

/* ================= TABLE ================= */
.item-table th,
.item-table td{
    border:1px solid #000;
    padding:4px 6px;
    font-size:12px;
}

.item-table th{
    background:#cbd6e2;
    text-align:center;
}

/* ================= BOX ================= */
.vessel-box{
    border:1px solid #000;
    padding:3px 6px;
    font-weight:bold;
    width:98%;
}

/* ================= SIGNATURE LINE ================= */
.line{
    border-bottom:1px solid #000;
    display:inline-block;
    width:90%;
    height:14px;
}

.signature-space{
    height:60px;
}
</style>
</head>

<body>

{{-- ================= KOP ================= --}}
<div class="kop">

    <div class="logo">
        <img src="{{ public_path('assets/kopbjm.jpeg') }}">
    </div>

    <div class="company">
        <div class="company-name">PT. BUDI JAYA MARINE</div>
        Ruko Sentra Bisnis, Jl. Harapan Indah No. 33, Blok SS-2<br>
        Pejuang, Medan Satria, Bekasi 17131<br>
        Email: sasongko@budijayamarine.com; cs@budijayamarine.com<br>
        Mobile: 087770239693
    </div>

</div>

<div class="do-title">
    DELIVERY ORDER
</div>


{{-- ================= HEADER INFO ================= --}}
<table style="width:100%; margin-top:5px;">
<tr>
<td style="width:50%; vertical-align:top;">
    Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $doDate->format('d F Y') }}<br>
    To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $deliveryOrder->poMasuk->mitra_marine ?? '-' }}
</td>

<td style="width:50%; vertical-align:top;">

    <table style="width:80%; margin-left:auto; border-collapse:collapse;">
        <tr>
            <td style="width:45%; text-align:left;">NO</td>
            <td style="width:5%; text-align:center;">:</td>
            <td style="width:50%; text-align:left;">
                {{ $deliveryOrder->no_do }}
            </td>
        </tr>

        <tr>
            <td style="text-align:left;">Your P/O NO</td>
            <td style="text-align:center;">:</td>
            <td style="text-align:left;">
                {{ $deliveryOrder->poMasuk->no_po_klien ?? 'TBA' }}
            </td>
        </tr>

        <tr>
            <td style="text-align:left;">PO Authorization</td>
            <td style="text-align:center;">:</td>
            <td style="text-align:left;">TBA</td>
        </tr>
    </table>

</td>

</tr>
</table>

<br>

Please receive the following undermentioned goods in good order and condition by signing all copies attached.

<br><br>

<div class="vessel-box">
    VESSEL'S NAME : {{ $deliveryOrder->poMasuk->vessel ?? '-' }}
</div>

<br>

{{-- ================= ITEM TABLE ================= --}}
<table class="item-table">
<thead>
<tr>
    <th width="7%">Item</th>
    <th>Descriptions</th>
    <th width="15%">Quantity</th>
    <th width="15%">Unit</th>
</tr>
</thead>
<tbody>

@foreach($deliveryOrder->items as $index => $item)
<tr>
    <td class="center">{{ $index + 1 }}</td>
    <td>{{ $item->item }}</td>
    <td class="center">{{ $item->qty }}</td>
    <td class="center">{{ $item->unit }}</td>
</tr>
@endforeach

</tbody>
</table>

<br><br>

{{-- ================= DELIVERY & RECEIVED ================= --}}
<table style="width:100%; margin-top:10px;">
    <tr>
        <td width="55%">
            DELIVERY TO : {{ $deliveryOrder->poMasuk->mitra_marine ?? '-' }}
            <div class="line"></div>
        </td>

        <td width="45%">
            RECEIVED BY :
            <div class="line"></div>
        </td>
    </tr>

    {{-- BARIS DATE (SEJAJAR) --}}
    <tr>
        <td>
            DATE {{ $doDate->format('d-M-y') }}
        </td>
        <td>
            DATE {{ $doDate->format('d-M-y') }}
        </td>
    </tr>

    {{-- BARIS PT BJM --}}
    <tr>
        <td>
            <strong style="margin-top:6px">PT. BUDI JAYA MARINE</strong>
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
</table>

<br><br><br>

{{-- ================= SIGNATURE ================= --}}
<table style="width:100%;">
<tr>
<td width="50%" style="text-align:left;">
    <div class="signature-space"></div>
    AUTHORISED SIGNATURE
</td>
<td width="50%" class="center">
    <div class="signature-space"></div>
    AUTHORISED SIGNATURE & COMPANY/SHIP STAMP
</td>
</tr>
</table>



</body>
</html>
