@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Laporan Pengeluaran Transport - Bulan {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</h4>

    <form method="GET" class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pengeluaran_transport.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Plat Nomor</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transport as $index => $t)
                @foreach($t->items as $itemIndex => $item)
                <tr>
                    @if($itemIndex==0)
                        <td rowspan="{{ $t->items->count() }}">{{ $index+1 }}</td>
                        <td rowspan="{{ $t->items->count() }}">{{ $t->unit->nama_unit }}</td>
                        <td rowspan="{{ $t->items->count() }}">{{ $t->tanggal }}</td>
                    @endif
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ number_format($item->nominal,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3"><strong>Total {{ $t->unit->nama_unit }}</strong></td>
                    <td colspan="2"><strong>{{ number_format($t->total_amount,0,',','.') }}</strong></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong>Total Keseluruhan</strong></td>
                <td><strong>{{ number_format($total_all,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <button onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
</div>
@endsection
