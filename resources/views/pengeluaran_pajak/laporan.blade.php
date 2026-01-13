@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Laporan Pengeluaran Pajak Mobil - Bulan {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</h4>

    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pengeluaran_pajak.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Plat Nomor</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pajak as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->unit->nama_unit }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>{{ number_format($item->nominal,0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td><strong>{{ number_format($total,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <button onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
</div>
@endsection
