@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between">
            <h5 class="mb-0">Detail Pemasukan Marine</h5>

            <a href="{{ route('pemasukan-marine.index') }}"
               class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="fw-bold">No PO</label>
                    <p>{{ $pemasukanMarine->poMasuk->no_po_klien ?? '-' }}</p>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold">Company</label>
                    <p>{{ $pemasukanMarine->poMasuk->mitra_marine ?? '-' }}</p>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold">Vessel</label>
                    <p>{{ $pemasukanMarine->poMasuk->vessel ?? '-' }}</p>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Tanggal</label>
                    <p>{{ $pemasukanMarine->tanggal }}</p>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Nama Pengirim</label>
                    <p>{{ $pemasukanMarine->nama_pengirim }}</p>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Metode</label>
                    <p>{{ $pemasukanMarine->metode }}</p>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Nominal</label>
                    <p>{{ number_format($pemasukanMarine->nominal, 2, ',', '.') }}</p>
                </div>

                <div class="col-md-12">
                    <label class="fw-bold">Keterangan</label>
                    <p>{{ $pemasukanMarine->keterangan ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">Bukti Transfer</label>
                    <div class="mt-2">
                        @if($pemasukanMarine->bukti)
                            <img src="{{ asset('storage/'.$pemasukanMarine->bukti) }}"
                                 class="img-thumbnail"
                                 style="max-height:300px;">
                        @else
                            <p>-</p>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection