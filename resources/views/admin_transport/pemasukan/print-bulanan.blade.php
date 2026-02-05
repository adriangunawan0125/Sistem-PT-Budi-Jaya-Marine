<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Laporan Pemasukan Bulanan</title>

<style>
    body {
        font-family: "Times New Roman", DejaVu Serif, serif;
        font-size: 13px;
        color: #000;
    }

    table { width:100%; border-collapse:collapse; }

    .right { text-align:right; }
    .center { text-align:center; }
    .bold { font-weight:bold; }

    /* ================= KOP ================= */
    .kop {
        border-bottom:3px solid #000;
        margin-bottom:18px;
        padding-bottom:12px;
        margin-left:15px;
    }

    .logo img { width:110px; }

    .company {
        text-align:center;
        padding-right:120px;
    }

    .company-name {
        font-size:24px;
        font-weight:bold;
        margin-bottom:6px;
    }

    .company-address {
        font-size:12px;
        line-height:1.5;
    }

    /* ================= JUDUL ================= */
    .judul {
        text-align:center;
        margin-top:10px;
        margin-bottom:30px;
    }

    .judul h3 {
        margin:0;
        font-size:18px;
        letter-spacing:1px;
    }

    .periode {
        margin-bottom:15px;
        font-size:13px;
    }

    /* ================= TABLE ================= */
    .item-table th,
    .item-table td {
        border:1px solid #000;
        padding:7px;
        font-size:13px;
    }

    .item-table th {
        background:#0076AF;
        color:#fff;
        text-align:center;
    }

    /* ================= MONEY ================= */
    .money { width:100%; border-collapse:collapse; }
    .money td { border:none; padding:0; }
    .rp { width:22px; text-align:left; }
    .val { text-align:right; }

    .total-row {
        background:#0076AF;
        color:#fff;
        font-weight:bold;
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

<!-- ================= JUDUL ================= -->
<div class="judul">
    <h3>LAPORAN PEMASUKAN BULANAN</h3>
</div>

<div class="periode">
    <b>Bulan :</b> {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
</div>

<!-- ================= TABLE ================= -->
<table class="item-table">
<thead>
<tr>
    <th width="5%">No</th>
    <th width="15%">Tanggal</th>
    <th width="20%">Mitra</th>
    <th width="12%">Kategori</th>
    <th>Deskripsi</th>
    <th width="18%">Nominal</th>
</tr>
</thead>

<tbody>
@foreach($pemasukan as $i => $p)
<tr>
    <td class="center">{{ $i+1 }}</td>

    <td class="center">
        {{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}
    </td>

    <td>
        {{ $p->mitra->nama_mitra ?? '-' }}
    </td>

    <td class="center">
        {{ ucfirst($p->kategori) }}
    </td>

    <td>
        {{ $p->deskripsi }}
    </td>

    <td>
        <table class="money">
            <tr>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($p->nominal,0,',','.') }}</td>
            </tr>
        </table>
    </td>
</tr>
@endforeach
</tbody>

<tfoot>
<tr class="total-row">
    <td colspan="5" class="right">TOTAL</td>
    <td>
        <table class="money">
            <tr>
                <td class="rp">Rp</td>
                <td class="val">{{ number_format($total,0,',','.') }}</td>
            </tr>
        </table>
    </td>
</tr>
</tfoot>
</table>

</body>
</html>
