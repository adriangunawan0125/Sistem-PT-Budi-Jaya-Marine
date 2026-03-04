<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PO Supplier</title>

<style>

body{
    font-family:"Times New Roman", DejaVu Serif, serif;
    font-size:13px;
    color:#000;
}

table{
    width:100%;
    border-collapse:collapse;
}

.center{ text-align:center; }
.right{ text-align:right; }
.bold{ font-weight:bold; }

.kop{
    border-bottom:2px solid #000;
    padding-bottom:8px;
    margin-bottom:15px;
}

.logo img{
    width:90px;
}

.company{
    text-align:center;
    padding-right:60px;
}

.company-name{
    font-size:20px;
    font-weight:bold;
}

.title{
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin-top:10px;
}

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

.total-row{
    font-weight:bold;
}

.money{
    width:100%;
}

.money td{
    border:none;
    padding:0;
}

.rp{ width:20px; }
.val{ text-align:right; }

.terms{
    font-size:12px;
    margin-top:20px;
}

.signature{
    margin-top:40px;
    text-align:right;
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


<!-- ================= TITLE ================= -->

<div class="title">PURCHASE ORDER</div>

<br>


<!-- ================= HEADER ================= -->

<table>

<tr>

<td width="60%">
<b>Order To :</b><br>
{{ $poSupplier->nama_perusahaan }}
</td>

<td width="40%">

<table style="width:auto">

<tr>
<td style="width:70px;"><b>PO No</b></td>
<td style="width:10px;">:</td>
<td>{{ $poSupplier->no_po_internal }}</td>
</tr>

<tr>
<td><b>Date</b></td>
<td>:</td>
<td>{{ $poDate->format('d F Y') }}</td>
</tr>

</table>

</td>
</tr>

</table>

<br>

Please, to place orders on you as follow :

<br><br>


<!-- ================= ITEM TABLE ================= -->

<table class="item-table">

<thead>

<tr>

<th width="5%">No</th>
<th>Item</th>
<th width="15%">Price</th>
<th width="8%">Qty</th>
<th width="10%">Unit</th>
<th width="18%">Amount</th>

</tr>

</thead>

<tbody>

@php $no = 1; @endphp

@foreach($poSupplier->items as $item)

<tr>

<td class="center">{{ $no++ }}</td>

<td>{{ $item->item }}</td>

<td>
<table class="money">
<tr>
<td class="rp">Rp</td>
<td class="val">{{ number_format($item->price_beli,0,',','.') }}</td>
</tr>
</table>
</td>

<td class="center">{{ $item->qty }}</td>

<td class="center">{{ $item->unit }}</td>

<td>
<table class="money">
<tr>
<td class="rp">Rp</td>
<td class="val">{{ number_format($item->amount,0,',','.') }}</td>
</tr>
</table>
</td>

</tr>

@endforeach


<tr class="total-row">

<td colspan="5" class="right">TOTAL</td>

<td>
<table class="money">
<tr>
<td class="rp">Rp</td>
<td class="val">{{ number_format($poSupplier->total_beli,0,',','.') }}</td>
</tr>
</table>
</td>

</tr>


@if($poSupplier->discount_amount > 0)

<tr class="total-row">

<td colspan="5" class="right" style="color:red;">DISCOUNT</td>

<td>
<table class="money">
<tr>
<td class="rp">Rp</td>
<td class="val" style="color:red;">
{{ number_format($poSupplier->discount_amount,0,',','.') }}
</td>
</tr>
</table>
</td>

</tr>

@endif


<tr class="total-row">

<td colspan="5" class="right">GRAND TOTAL</td>

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


<!-- ================= TERMS ================= -->

<div class="terms">

<b>Term and Conditions:</b>

<ol>

<li>Barang yang di supply harus sesuai yang di sepakati.</li>

<li>Pembeli berhak mengembalikan barang yang tidak sesuai dan mengembalikan ke penjual.</li>

<li>
Invoice ditujukan ke PT. Budi Jaya Marine dengan alamat:<br>
Ruko Sentra Bisnis, Jl. Harapan Indah No 3 Blok SS 2<br>
Pejuang, Medan Satria, Bekasi 17131
</li>

<li>Harga tersebut sudah all in dan di supply sesuai dengan permintaan kapal.</li>

</ol>

</div>


<!-- ================= SIGNATURE ================= -->

<div class="signature">

Approved by,<br><br><br><br><br>

<b>Budi Sasongko</b>

</div>


</body>
</html>