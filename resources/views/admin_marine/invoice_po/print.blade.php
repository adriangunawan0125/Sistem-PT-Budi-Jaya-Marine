<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice {{ $invoice->no_invoice }}</title>

<style>
body{
    font-family: Arial, sans-serif;
    font-size:13px;
    color:#000;
    margin:0;
    padding:0 40px;
}

table{
    width:100%;
    border-collapse:collapse;
}

.center{text-align:center;}
.right{text-align:right;}
.bold{font-weight:bold;}

/* ================= HEADER ================= */
.kop{
    border-bottom:2px solid #2b6ea6;
    padding-bottom:10px;
    margin-bottom:15px;
    position:relative;
}

.logo{
    position:absolute;
    left:0;
    top:5px;
}


.logo img{
    width:80px;
}

.company{
    text-align:center;
    line-height:1.6;
}

.company-name{
    font-size:20px;
    font-weight:bold;
    letter-spacing:1px;
}

/* ================= TITLE ================= */
.invoice-title{
    font-weight:bold;
    margin:10px 0 5px 0;
}

/* ================= TABLE ================= */
.item-table th,
.item-table td{
    border:1px solid #000;
    padding:6px;
    font-size:12px;
}

.item-table th{
    background:#2b6ea6;
    color:#fff;
    text-align:center;
}

/* ================= FOOTER ================= */
.footer-note{
    font-size:12px;
    margin-top:10px;
}

.signature{
    margin-top:40px;
}
</style>
</head>

<body>

{{-- ================= KOP ================= --}}
<div style="border-bottom:2px solid #2b6ea6; padding-bottom:12px; margin-bottom:15px;">

    <table style="width:100%; border-collapse:collapse;">
        <tr>

            {{-- KOLOM KIRI (LOGO) --}}
            <td width="20%" style="text-align:left; vertical-align:middle;">
                <img src="{{ public_path('assets/kopbjm.jpeg') }}" width="85">
            </td>

            {{-- KOLOM TENGAH (TEXT CENTER SEBENARNYA) --}}
            <td width="60%" style="text-align:center; vertical-align:middle;">

                <div style="font-size:20px; font-weight:bold; letter-spacing:1px;">
                    PT. BUDI JAYA MARINE
                </div>

                Ruko Sentra Bisnis, Jl. Harapan Indah No.3 Blok SS 2,<br>
                Pejuang, Medan Satria, Bekasi 17131<br>

                087770239693 | Email: sasongko@budijayamarine.com; cs@budijayamarine.com

            </td>

            {{-- KOLOM KANAN (KOSONG UNTUK BALANCE) --}}
            <td width="20%"></td>

        </tr>
    </table>

</div>




{{-- ================= INVOICE TITLE ================= --}}
<div class="invoice-title">
    INVOICE {{ $invoice->no_invoice }}
</div>
Date: {{ $invoiceDate->format('d F Y') }}

<br><br>

{{-- ================= BILL TO & FOR ================= --}}
<table style="margin-top:15px;">
<tr>

    {{-- LEFT : BILL TO --}}
    <td width="48%" valign="top">

        <strong>BILL TO :</strong><br><br>
        {{ $invoice->poMasuk->mitra_marine ?? '-' }}<br>
        {!! nl2br($invoice->poMasuk->alamat ?? '') !!}

    </td>

    {{-- SPACER (RUANG TENGAH) --}}
    <td width="4%"></td>

    {{-- RIGHT : FOR --}}
    <td width="48%" valign="top">

        <strong>FOR</strong>

        <table style="width:100%; margin-top:8px; border-collapse:collapse;">
            <tr>
                <td width="45%">Authorization No</td>
                <td width="5%" class="center">:</td>
                <td width="50%">{{ $invoice->authorization_no ?? '-' }}</td>
            </tr>

            <tr>
                <td>Vessel</td>
                <td class="center">:</td>
                <td>{{ $invoice->poMasuk->vessel ?? '-' }}</td>
            </tr>

            <tr>
                <td>PO No / TQD</td>
                <td class="center">:</td>
                <td>{{ $invoice->poMasuk->no_po_klien ?? '-' }}</td>
            </tr>

            <tr>
                <td>Periode</td>
                <td class="center">:</td>
                <td>{{ $invoice->periode ?? '-' }}</td>
            </tr>

            <tr>
                <td>Manpower</td>
                <td class="center">:</td>
                <td>{{ $invoice->manpower ?? '-' }}</td>
            </tr>
        </table>

    </td>

</tr>
</table>


<br>

{{-- ================= ITEM TABLE ================= --}}
<table class="item-table">
<thead>
<tr>
    <th width="5%">No</th>
    <th>PART NAME / DESCRIPTION</th>
    <th width="8%">QTY</th>
    <th width="10%">Unit</th>
    <th width="15%">PRICE</th>
    <th width="17%">AMOUNT</th>
</tr>
</thead>
<tbody>

@foreach($invoice->items as $index => $item)
<tr>
    <td class="center">{{ $index+1 }}</td>
    <td>
        {{ $item->description }}
    </td>
    <td class="center">{{ $item->qty }}</td>
    <td class="center">{{ $item->unit }}</td>
    <td class="right">
        Rp {{ number_format($item->price,0,',','.') }}
    </td>
    <td class="right">
        Rp {{ number_format($item->amount,0,',','.') }}
    </td>
</tr>
@endforeach

{{-- ================= TOTAL SECTION ================= --}}
<tr>
    <td colspan="5" class="right">SUBTOTAL</td>
    <td class="right">
        Rp {{ number_format($invoice->subtotal,0,',','.') }}
    </td>
</tr>

@if($invoice->discount_amount > 0)
<tr>
    <td colspan="5" class="right">
        DISCOUNT
        @if($invoice->discount_type == 'percent')
            ({{ $invoice->discount_value }}%)
        @endif
    </td>
    <td class="right">
        - Rp {{ number_format($invoice->discount_amount,0,',','.') }}
    </td>
</tr>
@endif

<tr>
    <td colspan="5" class="right bold">GRAND TOTAL</td>
    <td class="right bold">
        Rp {{ number_format($invoice->grand_total,0,',','.') }}
    </td>
</tr>


</tbody>
</table>


{{-- ================= PAYMENT INFO ================= --}}
<div class="footer-note">
<br>
Please transfer payment full amount (exclude bank charges) to :<br><br>

<strong>PT. BUDI JAYA MARINE</strong><br>
BANK : BANK NEGARA INDONESIA (BNI)<br>
A/C No : 1553722532<br>
Payment Term : TT Payment
</div>

<br><br>

<table>
<tr>
<td width="60%"></td>
<td width="40%" class="center">
    Bekasi, {{ $invoiceDate->format('d F Y') }}<br>

    <img src="{{ public_path('assets/stamp.png') }}" width="120"><br>
    <strong>Nita Nilasari</strong><br>
    (Finance Manager)
</td>
</tr>
</table>

</body>
</html>
