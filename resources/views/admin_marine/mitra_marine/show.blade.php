@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Detail Mitra Marine</h4>

    {{-- INFO MITRA --}}
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="180">Nama Perusahaan</th>
                    <td>: {{ $mitra->nama_mitra }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>: {{ $mitra->address }}</td>
                </tr>
                <tr>
                    <th>No Telp</th>
                    <td>: {{ $mitra->telp }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- DAFTAR KAPAL --}}
    <div class="card">
        <div class="card-header">
            <strong>Daftar Kapal</strong>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="60">No</th>
                        <th>Nama Vessel</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mitra->vessels as $i => $v)
                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>
                            <td>{{ $v->nama_vessel }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">
                                Belum ada kapal
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('mitra-marine.index') }}" class="btn btn-secondary mt-3">
        Kembali
    </a>

</div>
@endsection
