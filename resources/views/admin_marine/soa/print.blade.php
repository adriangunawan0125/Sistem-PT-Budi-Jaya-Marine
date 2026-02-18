<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>SOA</title>

<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        margin:0;
    }

    /* Lebar A4 aman */
    .page{
        width:690px;
        margin:60px auto;
    }

    .center{text-align:center;}
    .right{text-align:right;}

    .company{
        font-size:11px;
        font-weight:bold;
    }

    .subtitle{
        font-size:10px;
        margin-top:6px;
        margin-bottom:15px;
        font-weight:bold;
        text-decoration: underline;
    }

    .header td{
        border:none;
        font-size:9px;
        padding:2px 2px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
    }

    th{
        font-size:7.5px;
        background:#cfcfcf;
        font-weight:bold;
        border:1px solid #000;
        padding:2px;
        text-align:center;
    }

    td{
        font-size:7px;          /* diperkecil lagi */
        border:1px solid #000;
        padding:2px;
        word-break:break-word;
        vertical-align:top;
    }

    .red{
        color:red;
        font-weight:bold;
    }

    .total-row td{
        font-weight:bold;
        font-size:7.5px;
    }
</style>
</head>

<body>

<div class="page">

    {{-- COMPANY --}}
    <div class="center company">
        PT. BUDI JAYA MARINE
    </div>

    <div class="center" style="font-size:9px; margin-bottom:20px;">
        Ruko Sentra Bisnis, Jl. Harapan Indah No. 3 Blok SS 2,
        Pejuang, Medan Satria, Bekasi 17132
    </div>

    <div class="center subtitle">
        STATEMENT OF ACCOUNT (SOA)
    </div>

    {{-- HEADER --}}
    <table class="header">
        <tr>
            <td width="15%"><b>DEBT DEBTOR</b></td>
            <td width="40%">: {{ $soa->debtor }}</td>

            <td width="20%" class="right"><b>STATEMENT DATE</b></td>
            <td width="25%">: {{ \Carbon\Carbon::parse($soa->statement_date)->format('d F Y') }}</td>
        </tr>

        <tr>
            <td><b>ADDR ADDRESS</b></td>
            <td>: {{ $soa->address }}</td>

            <td class="right"><b>TERMIN</b></td>
            <td>: {{ $soa->termin }}</td>
        </tr>

    </table>

    <br>

    {{-- TABLE --}}
    <table>
       <table>
    <thead>
        <tr>
            <th style="width:3%;">No.</th>
            <th style="width:14%;">Customer Name</th>
            <th style="width:8%;">Name Vessel</th>
            <th style="width:14%;">Job Details</th>
            <th style="width:8%;">PO Number</th>
            <th style="width:8%;">Invoice Date</th>
            <th style="width:11%;">Invoice No.</th>
            <th style="width:9%;">Pending</th>
            <th style="width:7%;">Accepted</th>
            <th style="width:4%;">Days</th>
            <th style="width:6%;">Remarks</th>
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
            <td class="center">{{ $i+1 }}</td>
            <td>{{ $po->mitra_marine ?? '-' }}</td>
            <td class="center">{{ $po->vessel ?? '-' }}</td>
        <td>{{ $item->job_details ?? '-' }}</td>
            <td class="center">{{ $po->no_po_klien ?? '-' }}</td>
            <td class="center">{{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('m/d/Y') }}</td>
            <td class="center">{{ $invoice->no_invoice }}</td>
            <td class="right">{{ number_format($invoice->grand_total,2,',','.') }}</td>
            <td class="center">
                {{ $item->acceptment_date
                    ? \Carbon\Carbon::parse($item->acceptment_date)->format('m/d/Y')
                    : '-' }}
            </td>
            <td class="center {{ $days > 30 ? 'red' : '' }}">
                {{ $days }}
            </td>
            <td class="{{ $days > 30 ? 'red' : '' }}">
                {{ $remark }}
            </td>
        </tr>

        @endforeach

        <tr class="total-row">
            <td colspan="7" class="center">TOTAL</td>
            <td class="right">
                {{ number_format($total,2,',','.') }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        </tbody>
    </table>

</div>

</body>
</html>
