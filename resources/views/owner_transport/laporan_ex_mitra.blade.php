@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Laporan Ex Mitra</h4>

    <div class="alert alert-info">
        Total Ex Mitra: <strong>{{ $total }}</strong>
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Mitra</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Kontrak Mulai</th>
                    <th>Kontrak Berakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mitras as $no => $mitra)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $mitra->nama_mitra }}</td>
                    <td>{{ $mitra->alamat }}</td>
                    <td>{{ $mitra->no_hp }}</td>
                    <td>{{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('mitra.detail', $mitra->id) }}" class="btn btn-sm btn-info">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada ex mitra</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
