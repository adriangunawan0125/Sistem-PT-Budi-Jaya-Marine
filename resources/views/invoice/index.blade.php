@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Data Invoice</h4>

    <a href="{{ route('invoice.create') }}" class="btn btn-primary mb-3">+ Invoice Baru</a>

   <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Mitra</th>
                <th>Total Tagihan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->mitra->nama_mitra }}</td>
                <td>Rp {{ number_format($row->total_amount) }}</td>
                <td>
                    <a href="{{ route('invoice.show', $row->mitra->id) }}" class="btn btn-info btn-sm">
                        Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
