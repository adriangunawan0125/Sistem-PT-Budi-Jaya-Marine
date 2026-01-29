@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Detail Calon Mitra</h4>

    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td>{{ $calonmitra->nama }}</td>
        </tr>

        <tr>
            <th>No HP</th>
            <td>{{ $calonmitra->no_handphone }}</td>
        </tr>

        <tr>
            <th>Alamat</th>
            <td>{{ $calonmitra->alamat }}</td>
        </tr>

        <tr>
            <th>Tanggal Daftar</th>
            <td>
                {{ $calonmitra->created_at->format('d M Y H:i') }}
            </td>
        </tr>
    </table>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        Kembali
    </a>
</div>
@endsection
