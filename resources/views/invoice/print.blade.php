<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #000;
    }

    .container { width: 100%; }

    .row { width: 100%; display: table; }
    .col { display: table-cell; vertical-align: top; }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .bold { font-weight: bold; }

    .mb-1 { margin-bottom: 5px; }
    .mb-2 { margin-bottom: 10px; }
    .mb-3 { margin-bottom: 20px; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #555;
        color: #fff;
        padding: 6px;
        border: 1px solid #000;
        font-size: 10px;
        white-space: nowrap;
    }

    td {
        padding: 5px;
        border: 1px solid #000;
        vertical-align: middle;
    }

    .no-border td {
        border: none;
        padding: 2px;
    }

    .total-box td {
        font-size: 12px;
        font-weight: bold;
        border: 1px solid #000;
        padding: 8px;
    }

    .money {
        text-align: right;
        white-space: nowrap;
    }

    .money span {
        display: inline-block;
    }

    .money .cur {
        width: 28px;
        text-align: left;
    }

    .money .val {
        min-width: 100px;
        text-align: right;
    }

    .date {
        white-space: nowrap;
        text-align: center;
    }

    .img-proof {
        width: 160px;
        border: 1px solid #000;
    }
</style>
</head>

<body>
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="row mb-3">
    <div class="col">
        <div class="bold">PT. BUDI JAYA MARINE</div>
        <div>Bill To:</div>
        <div class="bold">{{ $invoice->mitra->nama_mitra }}</div>
        <div>
            {{ optional($invoice->mitra->unit)->merek ?? '-' }}
            ({{ optional($invoice->mitra->unit)->nama_unit ?? '-' }})
        </div>
    </div>

    <div class="col text-right">
        <div class="bold" style="font-size:40px;">INVOICE</div>
        <div>Invoice #: {{ $invoiceNumber }}</div>
        <div>Date: {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d M Y') }}</div>
        <div>Payment Terms: Transfer</div>
        <div>Due Date: <b>{{ now()->format('d M Y') }}</b></div>
    </div>
</div>

{{-- ================= TABLE ITEM ================= --}}
<table class="mb-3">
<thead>
<tr>
    <th width="5%">NO</th>
    <th width="30%">ITEM</th>
    <th width="15%">TANGGAL TF</th>
    <th width="16%">CICILAN</th>
    <th width="16%">TAGIHAN</th>
    <th width="18%">AMOUNT</th>
</tr>
</thead>
<tbody>
@foreach ($items as $i => $item)
<tr>
    <td class="text-center">{{ $i + 1 }}</td>
    <td>{{ $item->item }}</td>
    <td class="date">
        {{ $item->tanggal
            ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y')
            : '-' }}
    </td>
    <td class="money">
        <span class="cur">IDR</span>
        <span class="val">{{ number_format($item->cicilan) }}</span>
    </td>
    <td class="money">
        <span class="cur">IDR</span>
        <span class="val">{{ number_format($item->tagihan) }}</span>
    </td>
    <td class="money">
        <span class="cur">IDR</span>
        <span class="val">{{ number_format($item->amount) }}</span>
    </td>
</tr>
@endforeach
</tbody>
</table>

{{-- ================= TOTAL ================= --}}
<table class="total-box mb-3">
<tr>
    <td width="85%">Balance Due</td>
    <td width="15%" class="money">
        <span class="cur">IDR</span>
        <span class="val">{{ number_format($invoice->total) }}</span>
    </td>
</tr>
</table>

{{-- ================= NOTES ================= --}}
<table class="no-border mb-3">
<tr>
<td>
<b>Notes:</b><br>
Pembayaran melalui rekening perusahaan.<br>
BCA 5212309133 a/n PT. Budi Jaya Marine.<br><br>

<b>Terms:</b><br>
<i>
Apabila tidak melakukan pembayaran selama 3 hari berturut-turut,
maka unit akan ditarik ke pool.
</i>
</td>
</tr>
</table>

{{-- ================= BUKTI (FIX LOGIC) ================= --}}
@php
    // ambil ITEM TERAKHIR (BUKAN item lain yang punya gambar)
    $lastItem = $items->last();

    $lastTransfer = ($lastItem && $lastItem->gambar_transfer)
        ? $lastItem
        : null;

    $lastTrip = ($lastItem && $lastItem->gambar_trip)
        ? $lastItem
        : null;
@endphp

<table class="no-border" width="100%">
<tr>
<td width="50%" valign="top">
<b>Bukti Transfer Terakhir</b><br><br>
@if($lastTransfer)
<img src="{{ public_path('storage/'.$lastTransfer->gambar_transfer) }}" class="img-proof">
@else
<i>Tidak ada bukti transfer</i>
@endif
</td>

<td width="50%" valign="top" class="text-right">
<b>Bukti Perjalanan Terakhir</b><br><br>
@if($lastTrip)
<img src="{{ public_path('storage/'.$lastTrip->gambar_trip) }}" class="img-proof">
@else
<i>Tidak ada bukti perjalanan</i>
@endif
</td>
</tr>
</table>

</div>
</body>
</html>
