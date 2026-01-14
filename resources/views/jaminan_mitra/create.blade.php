@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Tambah Jaminan Mitra</h4>

    <form action="{{ route('jaminan_mitra.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Mitra</label>
            <select name="mitra_id" class="form-control" required>
                <option value="">-- Pilih Mitra --</option>
                @foreach($mitras as $m)
                    <option value="{{ $m->id }}">
                        {{ $m->nama_mitra }} - {{ $m->no_hp }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Jaminan</label>
            <input type="text" name="jaminan"
                   class="form-control"
                   placeholder="Contoh: BPKB Motor" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Jaminan 1</label>
            <input type="file" name="gambar_1" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Jaminan 2</label>
            <input type="file" name="gambar_2" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Jaminan 3</label>
            <input type="file" name="gambar_3" class="form-control">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('jaminan_mitra.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
