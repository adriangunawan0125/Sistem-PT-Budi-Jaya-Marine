@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Laporan Pemasukan Harian</h3>

    <!-- Filter Tanggal -->
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Bukti</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemasukan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td>
                    @if($item->gambar)
                        <img src="{{ asset('storage/'.$item->gambar) }}" alt="Bukti" width="50">
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>{{ number_format($total, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
