@extends('layouts.app')

@section('content')

<style>
.soa-paper {
    background:#ffffff;
    padding:30px;
    font-size:12px;
    font-family: Arial, Helvetica, sans-serif;
}

.soa-title {
    text-align:center;
    font-weight:600;
}

.soa-title h4 {
    margin:0;
}

.soa-subtitle {
    text-align:center;
    margin-top:10px;
    margin-bottom:20px;
    font-weight:600;
    text-decoration: underline;
}

.header-info {
    width:100%;
    margin-bottom:20px;
}

.header-info td {
    padding:4px 6px;
    vertical-align:top;
}

.table-wrapper {
    overflow-x:auto;
}

.main-table {
    min-width:1200px;
    width:100%;
    border-collapse:collapse;
}

/* gunakan border default bootstrap */
.main-table th,
.main-table td {
    border:1px solid #dee2e6;
    padding:6px;
}

.main-table thead th {
    text-align:center;
    font-weight:600;
}

.text-center { text-align:center; }
.text-right { text-align:right; }

.total-row td {
    font-weight:600;
}
</style>


<div class="soa-paper">

{{-- ACTION --}}
<div class="mb-3 text-end">

    <a href="{{ route('soa.print', $soa->id) }}" 
       target="_blank"
       class="btn btn-success btn-sm">
        <i class="fa fa-print"></i> Print
    </a>

    <a href="{{ route('soa.edit', $soa->id) }}" 
       class="btn btn-primary btn-sm">
        Edit SOA
    </a>

    <form action="{{ route('soa.destroy', $soa->id) }}" 
          method="POST" 
          class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Yakin hapus SOA ini?')">
            Hapus SOA
        </button>
    </form>

</div>


{{-- COMPANY --}}
<div class="soa-title">
    <h4>PT. BUDI JAYA MARINE</h4>
    <div>
        Ruko Sentra Bisnis, Jl. Harapan Indah No. 3 Blok SS 2,
        Pejuang, Medan Satria, Bekasi 17132
    </div>
</div>

<div class="soa-subtitle">
    STATEMENT OF ACCOUNT (SOA)
</div>


{{-- HEADER --}}
<table class="header-info">
<tr>
    <td width="15%"><b>DEBTOR</b></td>
    <td width="35%">: {{ $soa->debtor }}</td>

    <td width="20%" class="text-right"><b>STATEMENT DATE</b></td>
    <td width="30%">: {{ \Carbon\Carbon::parse($soa->statement_date)->format('d F Y') }}</td>
</tr>

<tr>
    <td><b>ADDRESS</b></td>
    <td>: {{ $soa->address }}</td>

    <td class="text-right"><b>TERMIN</b></td>
    <td>: {{ $soa->termin }}</td>
</tr>

<tr>
    <td></td>
    <td></td>

    <td class="text-right"><b>Page</b></td>
    <td>: 1 of 1</td>
</tr>
</table>


{{-- TABLE --}}
<div class="table-wrapper">
<table class="main-table">
    <thead class="table-light">
        <tr>
            <th>No.</th>
            <th>Customer Name</th>
            <th>Name Vessel</th>
            <th>Job Details</th>
            <th>PO Number</th>
            <th>Invoice Date</th>
            <th>Invoice No.</th>
            <th>PENDING PAYMENT</th>
            <th>ACCEPTED DATE</th>
            <th>Days</th>
            <th>REMARKS</th>
        </tr>
    </thead>

    <tbody>
@php $total = 0; @endphp

@foreach($soa->items as $i => $item)

@php
$invoice = $item->invoice;
$po = $invoice->poMasuk ?? null;

$statementDate = \Carbon\Carbon::parse($soa->statement_date);
$days = $item->acceptment_date
    ? \Carbon\Carbon::parse($item->acceptment_date)->diffInDays($statementDate)
    : 0;

$total += $invoice->grand_total;

$remark = $days > 30 ? 'OVER DUE TIME' : ($item->remarks ?? '');
@endphp

<tr>
    <td class="text-center">{{ $i+1 }}</td>
    <td>{{ $po->mitra_marine ?? '-' }}</td>
    <td class="text-center">{{ $po->vessel ?? '-' }}</td>
<td style="white-space: pre-line;">
    {{ $item->job_details ?? '-' }}
</td>

    <td class="text-center">{{ $po->no_po_klien ?? '-' }}</td>
    <td class="text-center">{{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d/m/Y') }}</td>
    <td class="text-center">{{ $invoice->no_invoice }}</td>
    <td class="text-right">{{ number_format($invoice->grand_total,0,',','.') }}</td>
    <td class="text-center">
        {{ $item->acceptment_date
            ? \Carbon\Carbon::parse($item->acceptment_date)->format('d/m/Y')
            : '-' }}
    </td>
    <td class="text-center">
        @if($days > 30)
            <span class="text-danger fw-semibold">{{ $days }}</span>
        @else
            {{ $days }}
        @endif
    </td>
    <td>
        @if($days > 30)
            <span class="text-danger fw-semibold">{{ $remark }}</span>
        @else
            {{ $remark }}
        @endif
    </td>
</tr>

@endforeach

<tr class="total-row">
    <td colspan="7" class="text-center">TOTAL</td>
    <td class="text-right">{{ number_format($total,0,',','.') }}</td>
    <td></td>
    <td></td>
    <td></td>
</tr>

    </tbody>
</table>
</div>

</div>

@endsection
