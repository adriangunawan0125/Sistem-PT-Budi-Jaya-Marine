@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Detail Pemasukan Transport</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Tanggal</div>
                <div class="col-md-9">
                    {{ \Carbon\Carbon::parse($pemasukan->tanggal)->format('d-m-Y') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Mitra</div>
                <div class="col-md-9">
                    {{ $pemasukan->mitra->nama_mitra ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Kategori</div>
                <div class="col-md-9">
                    {{ ucfirst($pemasukan->kategori) }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Deskripsi</div>
                <div class="col-md-9">
                    {{ $pemasukan->deskripsi }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nominal</div>
                <div class="col-md-9">
                    <span class="fw-bold text-success">
                        Rp {{ number_format($pemasukan->nominal,0,',','.') }}
                    </span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 fw-bold">Bukti</div>
                <div class="col-md-9">
                    @if($pemasukan->gambar)
                        <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}"
                             class="img-fluid border rounded"
                             style="max-height:300px;">
                    @else
                        <span class="text-muted">Tidak ada gambar</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('pemasukan.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>

</div>
@endsection