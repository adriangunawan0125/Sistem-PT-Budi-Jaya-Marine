@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Detail Mitra</h4>

    <div class="row mt-4">

        {{-- KOLOM KIRI --}}
        <div class="col-md-6">

            <div class="mb-3">
                <label class="fw-semibold">Nama Mitra</label>
                <div class="form-control bg-light">
                    {{ $mitra->nama_mitra }}
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Unit</label>
                <div class="form-control bg-light">
                    {{ $mitra->unit->nama_unit ?? '-' }}
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">No HP</label>
                <div class="form-control bg-light">
                    {{ $mitra->no_hp }}
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Alamat</label>
                <div class="form-control bg-light" style="min-height:80px">
                    {{ $mitra->alamat }}
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-md-6">

            @php
                $hariIni = \Carbon\Carbon::today();
                $kontrakBerakhir = $mitra->kontrak_berakhir
                    ? \Carbon\Carbon::parse($mitra->kontrak_berakhir)
                    : null;

                // default AKTIF kalau tanggal belum diisi
                $aktif = !$kontrakBerakhir || $hariIni->lte($kontrakBerakhir);
            @endphp

            <div class="mb-3">
                <label class="fw-semibold">Status</label><br>
                @if($aktif)
                    <span class="badge bg-success text-white px-3 py-2 d-inline-block text-center"
                          style="min-width:120px">
                        Aktif
                    </span>
                @else
                    <span class="badge bg-danger text-white px-3 py-2 d-inline-block text-center"
                          style="min-width:120px">
                        Tidak Aktif
                    </span>
                @endif
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Kontrak Mulai</label>
                <div class="form-control bg-light">
                    {{ $mitra->kontrak_mulai
                        ? $mitra->kontrak_mulai->format('d-m-Y')
                        : '-' }}
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Kontrak Berakhir</label>
                <div class="form-control bg-light">
                    {{ $mitra->kontrak_berakhir
                        ? $mitra->kontrak_berakhir->format('d-m-Y')
                        : '-' }}
                </div>
            </div>

        </div>
    </div>

    {{-- BUTTON --}}
    <div class="mt-4">
        <a href="{{ url('/admin-transport/mitra') }}"
           class="btn btn-secondary">
            Kembali
        </a>

        <a href="{{ url('/admin-transport/mitra/'.$mitra->id.'/edit') }}"
           class="btn btn-warning">
            Edit
        </a>
    </div>

</div>
@endsection
