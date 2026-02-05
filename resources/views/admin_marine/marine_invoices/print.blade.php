<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>

<style>
    body {
        font-family: "Times New Roman", DejaVu Serif, serif;
        font-size: 13px;
        color: #000;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .right { text-align: right; }
    .center { text-align: center; }
    .bold { font-weight: bold; }

    /* ================= KOP ================= */
    .kop {
        border-bottom: 3px solid #000;
        margin-bottom: 18px;
        padding-bottom: 12px;
        margin-left: 15px;
    }

    .logo { width: 120px; }
    .logo img { width: 110px; }

    .company {
        text-align: center;
        padding-right: 120px;
    }

    .company-name {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 6px;
    }

    .company-address {
        font-size: 12px;
        line-height: 1.5;
    }

    /* ================= INFO ================= */
    .info-table td {
        padding: 4px 6px;
        font-size: 13px;
    }

    .right-info {
        padding-left: 20px;
    }

    /* ================= ITEM TABLE ================= */
    .item-table th,
    .item-table td {
        border: 1px solid #000;
        padding: 7px;
        font-size: 13px;
    }

    .item-table th {
        background: #0076AF;
        color: #fff;
        text-align: center;
    }

    /* ================= MONEY ================= */
    .money {
        width: 100%;
        border-collapse: collapse;
    }

    .money td {
        border: none;
        padding: 0;
    }

    .rp {
        width: 22px;
        text-align: left;
    }

    .val {
        text-align: right;
    }

    .total-row {
        background: #0076AF;
        color: #fff;
        font-weight: bold;
    }
</style>
</head>

<body>

<!-- ================= KOP ================= -->
<table class="kop">
<tr>
    <td class="logo">
        <img src="{{ public_path('assets/kopbjm.jpeg') }}">
    </td>
    <td class="company">
        <div class="company-name">PT. BUDI JAYA MARINE</div>
        <div class="company-address">
            Ruko Sentra Bisnis Jl. Harapan Indah Blok SS2 No.3<br>
            RT/RW.003/007 Pejuang, Medan Satria, Kota Bekasi 17132<br>
            Email : sasongko@budijayamarine.com | cs@budijayamarine.com<br>
            Mobile : 0877 7023 9693
        </div>
    </td>
</tr>
</table>

<!-- ================= INFO ================= -->
<table class="info-table" style="margin-bottom:20px;">
<tr>
    <td width="50%">
        <b>Project</b><br>
        {{ $invoice->project }}<br><br>

        <b>Bill To</b><br>
        {{ $invoice->company->name }}<br>
        <span style="font-size:12px;">
            {{ $invoice->company->address }}
        </span>
    </td>

    <td width="50%" class="right-info">
        <table class="info-table">
            <tr><td>Invoice Date</td><td>: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td></tr>
            <tr><td>Period</td><td>: {{ \Carbon\Carbon::parse($invoice->period)->format('M Y') }}</td></tr>
            <tr><td>Authorization No</td><td>: {{ $invoice->authorization_no ?? '-' }}</td></tr>
            <tr><td>Vessel</td><td>: {{ $invoice->vessel ?? '-' }}</td></tr>
            <tr><td>PO No</td><td>: {{ $invoice->po_no ?? '-' }}</td></tr>
            <tr><td>Manpower</td><td>: {{ $invoice->manpower }}</td></tr>
        </table>
    </td>
</tr>
</table>

<!-- ================= ITEMS ================= -->
<table class="item-table">
<thead>
<tr>
    <th width="5%">No</th>
    <th width="35%">Part Name / Description</th>
    <th width="8%">Qty</th>
    <th width="10%">Unit</th>
    <th width="16%">Price</th>
    <th width="26%">Amount</th>
</tr>
</thead>

<tbody>
@foreach($invoice->items as $i => $item)
<tr>
    <td class="center">{{ $i + 1 }}</td>
    <td>{{ $item->description }}</td>
    <td class="center">{{ $item->qty }}</td>
    <td class="center">{{ $item->unit }}</td>
    <td>
        <table class="money">
            <tr>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($item->price,0,',','.') }}</td>
            </tr>
        </table>
    </td>
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
</tbody>

<tfoot>
<tr>
    <td colspan="4" class="right bold">Subtotal</td>
    <td colspan="2">
        <table class="money">
            <tr>
                <td class="rp">Rp</td>
                <td class="val bold">{{ number_format($invoice->subtotal,0,',','.') }}</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="4" class="right bold">Down Payment</td>
    <td colspan="2">
        <table class="money">
            <tr>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($invoice->dp,0,',','.') }}</td>
            </tr>
        </table>
    </td>
</tr>
<tr class="total-row">
    <td colspan="4" class="right">GRAND TOTAL</td>
    <td colspan="2">
        <table class="money">
            <tr>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($invoice->grand_total,0,',','.') }}</td>
            </tr>
        </table>
    </td>
</tr>
</tfoot>
</table>

</body>
</html>
