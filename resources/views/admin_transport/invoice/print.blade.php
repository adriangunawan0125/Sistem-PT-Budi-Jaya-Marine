<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>

<style>

body{
font-family: DejaVu Sans, sans-serif;
font-size:10px;
color:#000;
margin:0;
}

.container{
width:100%;
}

table{
width:100%;
border-collapse:collapse;
}

.text-right{ text-align:right; }
.text-center{ text-align:center; }
.bold{ font-weight:bold; }

th{
background:#5a5a5a;
color:#fff;
padding:4px;
border:1px solid #000;
font-size:9px;
}

td{
border:1px solid #000;
padding:3px;
font-size:9px;
line-height:1.2;
}
.no-border td{
border:none;
padding:2px;
}

.header-table td{
border:none;
padding:2px 4px;
}

.date{
text-align:center;
}

.money{
text-align:right;
white-space:nowrap;
}

.notes{
border:none;
padding-top:10px;
}

.img-proof{
width:180px;
border:1px solid #000;
}

.item-col{
   font-size: 9px;
}
</style>
</head>

<body>

<div class="container">


{{-- HEADER --}}
<table class="header-table">

<tr>

<td width="50%" valign="top">

<b>PT. BUDI JAYA MARINE</b><br><br>

Bill To:<br>

<b>{{ $invoice->mitra->nama_mitra }}</b><br>

{{ optional($invoice->mitra->unit)->merek ?? '-' }}
({{ optional($invoice->mitra->unit)->nama_unit ?? '-' }})

</td>


<td width="50%" valign="top" class="text-right">

<div style="font-size:28px;font-weight:bold;">INVOICE</div>

{{ $invoiceNumber }}<br><br>

<!--Join tanggal  $invoice->mitra->created_at->format('d M Y') -->

<table class="header-table" style="width:60%; margin-left:auto">

<tr>
<td>Data:</td>
<td class="text-right">{{ $invoiceDate->format('d-M-y') }}</td>
</tr>

<tr>
<td>Payment Terms:</td>
<td class="text-right">Transfer</td>
</tr>

<tr>
<td>Due Date:</td>
<td class="text-right">{{ now()->format('d-M-y') }}</td>
</tr>

<tr>
<td class="bold">Balance Due:</td>
<td class="text-right bold">
IDR {{ number_format((float)$grandTotal,0,',','.') }}
</td>
</tr>

</table>

</td>

</tr>

</table>


<br>


{{-- TABLE ITEM --}}
<table>

<thead>

<tr>

<th width="4%">NO</th>
<th class="item-col">ITEM</th>
<th width="10%">Tanggal Bukti TF</th>
<th width="12%">CICILAN</th>
<th width="12%">TAGIHAN</th>
<th width="12%">AMOUNT</th>
</tr>

</thead>

<tbody>

@foreach ($items as $i => $item)

<tr>

<td class="text-center">
{{ $i+1 }}
</td>

<td class="item-col">
{{ $item->item }}
</td>

<td class="date">
{{ $item->tanggal_tf ? \Carbon\Carbon::parse($item->tanggal_tf)->format('j/n') : '-' }}
</td>

<td class="money">
IDR {{ number_format((float)$item->cicilan,0,',','.') }}
</td>

<td class="money">
IDR {{ number_format((float)$item->tagihan,0,',','.') }}
</td>

<td class="money">
IDR {{ number_format((float)$item->amount,0,',','.') }}
</td>

</tr>

@endforeach


{{-- SUBTOTAL --}}
<tr>
<td colspan="5" class="text-right">Subtotal</td>
<td class="money">
IDR {{ number_format((float)$grandTotal,0,',','.') }}
</td>
</tr>

<tr>
<td colspan="5" class="text-right">Admin Fee</td>
<td class="money"></td>
</tr>

<tr>
<td colspan="5" class="text-right">Claim Charge</td>
<td class="money"></td>
</tr>

<tr>
<td colspan="5" class="text-right bold">Total</td>
<td class="money bold">
IDR {{ number_format((float)$grandTotal,0,',','.') }}
</td>
</tr>

</tbody>

</table>


<br>


{{-- NOTES --}}
<table class="no-border notes">

<tr>

<td>

<b>Notes:</b><br>

<i>Pembayaran Melalui Rekening Perusahaan</i><br>

BCA 5212309133 a/n PT. BUDI JAYA MARINE

<br><br>

<b>Terms:</b><br>

<i>
Apabila tidak melakukan pembayaran setoran selama 3 hari berturut turut,<br>
maka Unit akan di tarik ke Pool
</i>

</td>

</tr>

</table>


<br>


{{-- IMAGE --}}
<table class="no-border">

<tr>

<td width="50%" valign="top">

<b>Bukti Transfer Terakhir</b><br><br>

@if(!empty($latestTransfers))

@foreach($latestTransfers as $img)

<img src="{{ storage_path('app/public/'.$img) }}" class="img-proof">

@endforeach

@else

<i>Tidak ada bukti transfer</i>

@endif

</td>



<td width="50%" valign="top" class="text-right">

<b>Bukti Perjalanan terakhir</b><br><br>

@if($latestTrip)

<img src="{{ storage_path('app/public/'.$latestTrip) }}" class="img-proof">

@else

<i>Tidak ada bukti perjalanan</i>

@endif

</td>

</tr>

</table>


</div>

</body>
</html>