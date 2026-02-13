<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quotation</title>

<style>
body{
    font-family:"Times New Roman", DejaVu Serif, serif;
    font-size:13px;
    color:#000;
}

table{ width:100%; border-collapse:collapse; }

.center{ text-align:center; }
.right{ text-align:right; }
.bold{ font-weight:bold; }

/* ================= KOP ================= */
.kop{
    border-bottom:2px solid #000;
    padding-bottom:8px;
    margin-bottom:15px;
}

.logo img{ width:90px; }

.company{
    text-align:center;
    padding-right:60px; /* geser supaya benar2 tengah */
}

.company-name{
    font-size:20px;
    font-weight:bold;
}

.company-address{
    font-size:12px;
    line-height:1.5;
}

/* ================= ITEM TABLE ================= */
.item-table th,
.item-table td{
    border:1px solid #000;
    padding:5px;
    font-size:12px;
}

.item-table th{
    background:#b7c7d9;
    font-weight:bold;
    text-align:center;
}

.sub-row{
    font-weight:bold;
}

.note-row{
    font-style:italic;
}

.total-row{
    font-weight:bold;
}

.money{
    width:100%;
    border-collapse:collapse;
}

.money td{
    border:none;
    padding:0;
}

.rp{ width:20px; }
.val{ text-align:right; }

.terms{
    margin-top:25px;
    font-size:12px;
}
</style>
</head>

<body>

<!-- ================= KOP ================= -->
<table class="kop">
<tr>
    <td width="15%" class="logo">
        <img src="{{ public_path('assets/kopbjm.jpeg') }}">
    </td>
    <td class="company">
        <div class="company-name">PT. BUDI JAYA MARINE</div>
        Ruko Sentra Bisnis, Jl. Harapan Indah No.3 Blok SS 2<br>
        Pejuang, Medan Satria, Bekasi 17131<br>
        Email: sasongko@budijayamarine.com; cs@budijayamarine.com<br>
        Mobile: 087770239693
    </td>
</tr>
</table>

<table width="100%">
<tr>
<td width="60%">
To<br>
Attn<br>
Quote No<br>
Project<br>
Vessel Name<br>
Place
</td>
<td width="40%">
: {{ $quotation->mitra->nama_mitra ?? '-' }}<br>
: {{ $quotation->attention ?? '-' }}<br>
: {{ $quotation->quote_no }}<br>
: {{ $quotation->project }}<br>
: {{ $quotation->vessel->nama_vessel ?? '-' }}<br>
: {{ $quotation->place }}
</td>
</tr>
</table>

<br>

Dear Sir/Madam,<br>
In compliance with your inquiry, we are pleased to offer you this quotation as follow :

<br><br>

<!-- ================= TABLE ================= -->
<table class="item-table">
<thead>
<tr>
    <th width="5%">No</th>
    <th>Item</th>
    <th width="15%">Price</th>
    <th width="8%">Qty</th>
    <th width="10%">Unit</th>
    <th width="18%">Total</th>
</tr>
</thead>
<tbody>

@php $no = 1; @endphp

@foreach($quotation->subItems as $sub)

<!-- SUB ITEM ROW (TIDAK MERGE KOLOM) -->
<tr class="sub-row">
    <td></td>
    <td class="bold">{{ $sub->name }}</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

@foreach($sub->items as $item)
<tr>
    <td class="center">{{ $no++ }}</td>
    <td>{{ $item->item }}</td>

    <td>
        <table class="money">
        <tr>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($item->price,0,',','.') }}</td>
        </tr>
        </table>
    </td>

    <td class="center">{{ $item->qty }}</td>
    <td class="center">{{ $item->unit }}</td>

    <td>
        <table class="money">
        <tr>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($item->total,0,',','.') }}</td>
        </tr>
        </table>
    </td>
</tr>
@endforeach

@endforeach

<!-- TOTAL ROW -->
<tr class="total-row">
    <td colspan="5" class="right">TOTAL</td>
    <td>
        <table class="money">
        <tr>
            <td class="rp">Rp</td>
            <td class="val">{{ number_format($grandTotal,0,',','.') }}</td>
        </tr>
        </table>
    </td>
</tr>

</tbody>
</table>

@if($quotation->termsConditions->count())
<div class="terms">
<br>
<b>Terms and conditions as follows:</b><br><br>

@foreach($quotation->termsConditions as $term)
{{ $loop->iteration }}. {{ $term->description }}<br>
@endforeach
</div>
@endif

<br><br>

Yours Faithfully &<br>
Best regards,<br>
<b>PT BUDI JAYA MARINE</b>

<br><br><br><br><br>

Suartini T.

</body>
</html>
