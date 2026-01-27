@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Detail Mitra: {{ $mitra->nama_mitra }}</h4>

    {{-- INFO MITRA --}}
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Nama Mitra</th>
                    <td>: {{ $mitra->nama_mitra }}</td>
                </tr>
                <tr>
                    <th>Unit</th>
                    <td>: {{ $mitra->unit->nama_unit ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>: {{ $mitra->alamat }}</td>
                </tr>
                <tr>
                    <th>No HP</th>
                    <td>: {{ $mitra->no_hp }}</td>
                </tr>
                <tr>
                    <th>Kontrak Mulai</th>
                    <td>: {{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kontrak Berakhir</th>
                    <td>: {{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- JAMINAN MITRA --}}
    <h5>Jaminan Mitra</h5>

    @if($mitra->jaminan)
        <div class="card mb-3">
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="200">Jenis Jaminan</th>
                        <td>: {{ $mitra->jaminan->jaminan }}</td>
                    </tr>
                    <tr>
                        <th>Bukti 1</th>
                        <td>
                            @if($mitra->jaminan->gambar_1)
                                <a href="{{ asset('storage/'.$mitra->jaminan->gambar_1) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$mitra->jaminan->gambar_1) }}" width="80" class="img-thumbnail">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Bukti 2</th>
                        <td>
                            @if($mitra->jaminan->gambar_2)
                                <a href="{{ asset('storage/'.$mitra->jaminan->gambar_2) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$mitra->jaminan->gambar_2) }}" width="80" class="img-thumbnail">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Bukti 3</th>
                        <td>
                            @if($mitra->jaminan->gambar_3)
                                <a href="{{ asset('storage/'.$mitra->jaminan->gambar_3) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$mitra->jaminan->gambar_3) }}" width="80" class="img-thumbnail">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Mitra ini belum memiliki jaminan.
        </div>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
