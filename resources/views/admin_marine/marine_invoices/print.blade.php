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

    .header {
        text-align: center;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .company-name {
        font-size: 16px;
        font-weight: bold;
    }

    .section {
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #000;
        padding: 6px;
    }

    .table th {
        background: #0d6efd;
        color: #fff;
        text-align: center;
    }

    .right { text-align: right; }
    .bold { font-weight: bold; }

    .total-row {
        background: #0d6efd;
        color: #fff;
        font-size: 12px;
    }
</style>
</head>

<body>

{{-- ================= HEADER ================= --}}
<div class="header">
    <div class="company-name">
        {{ $invoice->company->name }}
    </div>
    <div>
        {{ $invoice->company->address ?? '' }}
    </div>
</div>

{{-- ================= INVOICE INFO ================= --}}
<div class="section">
<table>
<tr>
    <td width="50%">
        <b>Project</b><br>
        {{ $invoice->project ?? '-' }}
        <br><br>

        <b>Bill To</b><br>
        {{ $invoice->company->name }}
    </td>

    <td width="50%">
        <table>
            <tr>
                <td>Invoice Date</td>
                <td>: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Period</td>
                <td>: {{ \Carbon\Carbon::parse($invoice->period)->format('M Y') }}</td>
            </tr>
            <tr>
                <td>Authorization No</td>
                <td>: {{ $invoice->authorization_no }}</td>
            </tr>
            <tr>
                <td>Vessel</td>
                <td>: {{ $invoice->vessel ?? '-' }}</td>
            </tr>
            <tr>
                <td>PO No</td>
                <td>: {{ $invoice->po_no ?? '-' }}</td>
            </tr>
            <tr>
                <td>Manpower</td>
                <td>: {{ $invoice->manpower ?? '-' }}</td>
            </tr>
        </table>
    </td>
</tr>
</table>
</div>

{{-- ================= ITEMS ================= --}}
<table class="table">
<thead>
<tr>
    <th width="5%">No</th>
    <th>Part Name / Description</th>
    <th width="8%">Qty</th>
    <th width="10%">Unit</th>
    <th width="15%">Price</th>
    <th width="15%">Amount</th>
</tr>
</thead>

<tbody>
@foreach($invoice->items as $i => $item)
<tr>
    <td align="center">{{ $i + 1 }}</td>
    <td>{{ $item->description }}</td>
    <td align="center">{{ $item->qty }}</td>
    <td align="center">{{ $item->unit }}</td>
    <td class="right">
        Rp {{ number_format($item->price,0,',','.') }}
    </td>
    <td class="right">
        Rp {{ number_format($item->amount,0,',','.') }}
    </td>
</tr>
@endforeach
</tbody>

<tfoot>
<tr>
    <td colspan="5" class="right bold">Subtotal</td>
    <td class="right bold">
        Rp {{ number_format($invoice->subtotal,0,',','.') }}
    </td>
</tr>
<tr>
    <td colspan="5" class="right bold">Down Payment</td>
    <td class="right">
        Rp {{ number_format($invoice->dp,0,',','.') }}
    </td>
</tr>
<tr class="total-row">
    <td colspan="5" class="right bold">GRAND TOTAL</td>
    <td class="right bold">
        Rp {{ number_format($invoice->grand_total,0,',','.') }}
    </td>
</tr>
</tfoot>
</table>

</body>
</html>
