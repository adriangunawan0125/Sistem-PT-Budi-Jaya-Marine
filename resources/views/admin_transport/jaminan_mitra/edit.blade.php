@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Jaminan Mitra</h4>

    <form action="{{ route('jaminan_mitra.update', $jaminanMitra->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Mitra</label>
            <input type="text" class="form-control"
                   value="{{ $jaminanMitra->mitra->nama_mitra }} - {{ $jaminanMitra->mitra->no_hp }}"
                   disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Jaminan</label>
            <input type="text" name="jaminan"
                   value="{{ $jaminanMitra->jaminan }}"
                   class="form-control" required>
        </div>

        @foreach(['gambar_1','gambar_2','gambar_3'] as $g)
        <div class="mb-3">
            <label class="form-label">{{ strtoupper(str_replace('_',' ', $g)) }}</label><br>
            @if($jaminanMitra->$g)
                <img src="{{ asset('storage/'.$jaminanMitra->$g) }}"
                     width="80" class="mb-2 rounded border"><br>
            @endif
            <input type="file" name="{{ $g }}" class="form-control">
        </div>
        @endforeach

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('jaminan_mitra.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
