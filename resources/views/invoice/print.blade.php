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
    }

    td {
        padding: 5px;
        border: 1px solid #000;
    }

    .no-border td {
        border: none;
        padding: 2px;
    }

    .total-box td {
        font-size: 12px;
        font-weight: bold;
    }

    /* ===== GAMBAR KECIL ===== */
    .img-proof {
        width: 160px;   /* kecil & rapi */
        height: auto;
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
    <div class="">{{ $invoice->mitra->unit->nama_unit ?? '-' }} - {{ $invoice->mitra->unit->merek ?? '-' }}</div>

</div>

    <div class="col text-right">
        <div class="bold" style="font-size:40px;">INVOICE</div>
        <div>
            Invoice #:
            {{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}/BJM/{{ now()->format('m/Y') }}
        </div>
        <div>Date: {{ optional($invoice->tanggal)->format('d-m-Y') }}</div>
        <div>Payment Terms: Transfer</div>
        <div>Due Date: <b>{{ now()->format('d-m-Y') }}</b></div>
    </div>
</div>

{{-- ================= TABLE ITEM ================= --}}
<table class="mb-3">
<thead>
<tr>
    <th width="5%">NO</th>
    <th width="40%">ITEM</th>
    <th width="10%">TANGGAL</th>
    <th width="15%">CICILAN</th>
    <th width="15%">TAGIHAN</th>
    <th width="15%">AMOUNT</th>
</tr>
</thead>
<tbody>
@foreach ($invoice->items as $i => $item)
<tr>
    <td class="text-center">{{ $i + 1 }}</td>
    <td>{{ $item->item }}</td>
    <td class="text-center">-</td>
    <td class="text-right">IDR {{ number_format($item->cicilan) }}</td>
    <td class="text-right">IDR {{ number_format($item->tagihan) }}</td>
    <td class="text-right">IDR {{ number_format($item->amount) }}</td>
</tr>
@endforeach
</tbody>
</table>

{{-- ================= TOTAL ================= --}}
<table class="total-box mb-3">
<tr>
    <td class="text-right">Balance Due</td>
    <td width="25%" class="text-right">
        IDR {{ number_format($invoice->total) }}
    </td>
</tr>
</table>

{{-- ================= NOTES ================= --}}
<table class="no-border mb-3">
<tr>
    <td>
        <b>Notes:</b><br>
        Pembayaran melalui rekening perusahaan.<br>
        BCA 5212309133 a/n PT. Budi Jaya Marine.<br>
        <br>
        Terms: <br>
        <i> Apabila tidak melakukan pembayaran <br>selama 3 hari berturut-turut,
        maka unit akan ditarik ke pool.</i>
       
    </td>
</tr>
</table>

{{-- ================= BUKTI GAMBAR ================= --}}
@php
    $lastTransfer = $invoice->items->whereNotNull('gambar_transfer')->last();
    $lastTrip = $invoice->items->whereNotNull('gambar_trip')->last();
@endphp



<table class="no-border" width="100%">
<tr>
    {{-- BUKTI TRANSFER --}}
    <td width="50%" valign="top" style="text-align: left; padding-left: 25px;">
        <b>Bukti Transfer Terakhir</b><br><br>

        @if($lastTransfer && $lastTransfer->gambar_transfer)
            <img
                src="{{ public_path('storage/'.$lastTransfer->gambar_transfer) }}"
                class="img-proof">
        @else
            <i>Tidak ada bukti transfer</i>
        @endif
    </td>

    {{-- BUKTI PERJALANAN --}}
    <td width="50%" valign="top" style="text-align: right; padding-right: 25px;">
        <b>Bukti Perjalanan Terakhir</b><br><br>

        @if($lastTrip && $lastTrip->gambar_trip)
            <img
                src="{{ public_path('storage/'.$lastTrip->gambar_trip) }}"
                class="img-proof">
        @else
            <i>Tidak ada bukti perjalanan</i>
        @endif
    </td>
</tr>
</table>

</div>
</body>
</html>
