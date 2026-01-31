@extends('layouts.app')

@section('content')
<div class="container">
    <h4>
        Laporan Pengeluaran Bulan
        {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
    </h4>

    <form method="GET"
          action="{{ route('pengeluaran_internal.laporan') }}"
          class="mb-3">
        <input type="month" name="bulan" value="{{ $bulan }}"
               class="form-control w-auto d-inline">
        <button class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pengeluaran_internal.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluaran as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><b>Total</b></td>
                <td><b>Rp {{ number_format($total,0,',','.') }}</b></td>
            </tr>
        </tbody>
    </table>

    <a href="{{ route('pengeluaran_internal.pdf', ['bulan' => $bulan]) }}"
   target="_blank"
   class="btn btn-danger">
   Export PDF
</a>

</div>
@endsection
