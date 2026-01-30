@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Detail Calon Mitra</h4>

    <table class="table table-bordered mb-4">
        <tr>
            <th width="200">Nama</th>
            <td>{{ $calonmitra->nama }}</td>
        </tr>
        <tr>
            <th>No Handphone</th>
            <td>{{ $calonmitra->no_handphone }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $calonmitra->alamat }}</td>
        </tr>
        <tr>
            <th>Jaminan</th>
            <td>{{ $calonmitra->jaminan }}</td>
        </tr>
        <tr>
            <th>Tanggal Daftar</th>
            <td>{{ $calonmitra->created_at->format('d M Y') }}</td>
        </tr>
    </table>

    {{-- GAMBAR JAMINAN --}}
    <div class="row mb-4">
        @if($calonmitra->gambar_1)
        <div class="col-md-4 mb-3">
            <p class="fw-bold">Gambar Jaminan 1</p>
            <img src="{{ asset('storage/'.$calonmitra->gambar_1) }}"
                 class="img-fluid img-thumbnail">
        </div>
        @endif

        @if($calonmitra->gambar_2)
        <div class="col-md-4 mb-3">
            <p class="fw-bold">Gambar Jaminan 2</p>
            <img src="{{ asset('storage/'.$calonmitra->gambar_2) }}"
                 class="img-fluid img-thumbnail">
        </div>
        @endif

        @if($calonmitra->gambar_3)
        <div class="col-md-4 mb-3">
            <p class="fw-bold">Gambar Jaminan 3</p>
            <img src="{{ asset('storage/'.$calonmitra->gambar_3) }}"
                 class="img-fluid img-thumbnail">
        </div>
        @endif
    </div>
{{-- AKSI --}}
<div class="d-flex mt-4">

    <a href="{{ url('/calon-mitra') }}"
       class="btn btn-secondary">
        Kembali
    </a>

    @if(!$calonmitra->is_checked)
        <form action="{{ url('/calon-mitra/'.$calonmitra->id.'/approve') }}"
              method="POST"
              style="margin-left:16px;">
            @csrf
            <button type="submit"
                    class="btn btn-success"
                    onclick="return confirm('Setujui calon mitra ini?')">
                Setujui Jadi Mitra
            </button>
        </form>
    @else
       <button type="button"
        class="btn btn-success ms-3"
        style="margin-left:16px;"
        disabled>
    Sudah Disetujui
</button>

    @endif

</div>

</div>

@endsection
