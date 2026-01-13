@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Laporan Daftar Mitra</h4>

    <div class="alert alert-info">
        Total Mitra: <strong>{{ $total }}</strong>
    </div>

    <div class="mb-3">
        <a href="/admin-transport/laporan/mitra/excel"
           class="btn btn-success">
            Export Excel
        </a>

        <a href="/admin-transport/laporan/mitra/pdf"
           class="btn btn-danger">
            Export PDF
        </a>

        <a href="/admin-transport/mitra"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Mitra</th>
                    <th>No Polisi</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <!--<th>Tanggal Bergabung</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($mitras as $no => $mitra)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $mitra->nama_mitra }}</td>
                    <td>{{ $mitra->unit->nama_unit ?? '-' }}</td>
                    <td>{{ $mitra->alamat }}</td>
                    <td>{{ $mitra->no_hp }}</td>
                   <!-- <td>{{ $mitra->created_at->format('d-m-Y') }}</td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
