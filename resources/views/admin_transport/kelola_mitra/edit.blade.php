@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Mitra</h4>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- SUCCESS TRIGGER (HIDDEN) --}}
    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    <form id="editForm" action="{{ url('/admin-transport/mitra/'.$mitra->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- NAMA MITRA --}}
        <div class="mb-3">
            <label>Nama Mitra</label>
            <input type="text"
                   name="nama_mitra"
                   class="form-control @error('nama_mitra') is-invalid @enderror"
                   value="{{ old('nama_mitra', $mitra->nama_mitra) }}"
                   required>
            @error('nama_mitra')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- UNIT --}}
        <div class="mb-3">
            <label>Unit</label>
            <select name="unit_id"
                    class="form-control @error('unit_id') is-invalid @enderror">
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}"
                        {{ old('unit_id', $mitra->unit_id) == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>
            @error('unit_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ALAMAT --}}
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat"
                      class="form-control @error('alamat') is-invalid @enderror"
                      rows="3"
                      required>{{ old('alamat', $mitra->alamat) }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NO HP --}}
        <div class="mb-3">
            <label>No HP</label>
            <input type="text"
                   name="no_hp"
                   class="form-control @error('no_hp') is-invalid @enderror"
                   value="{{ old('no_hp', $mitra->no_hp) }}"
                   required>
            @error('no_hp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- KONTRAK --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Kontrak Mulai</label>
                <input type="date"
                       name="kontrak_mulai"
                       class="form-control @error('kontrak_mulai') is-invalid @enderror"
                       value="{{ old('kontrak_mulai', optional($mitra->kontrak_mulai)->format('Y-m-d')) }}">
                @error('kontrak_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Kontrak Berakhir</label>
                <input type="date"
                       name="kontrak_berakhir"
                       class="form-control @error('kontrak_berakhir') is-invalid @enderror"
                       value="{{ old('kontrak_berakhir', optional($mitra->kontrak_berakhir)->format('Y-m-d')) }}">
                @error('kontrak_berakhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- BUTTON --}}
        <button class="btn btn-primary">Update</button>
        <a href="{{ url('/admin-transport/mitra') }}" class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>


<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memperbarui data...</div>
            </div>
        </div>
    </div>
</div>


<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                </div>
                <h5 class="fw-bold mb-2">Berhasil</h5>
                <div id="successText" class="text-muted"></div>
                <div class="mt-4">
                    <button class="btn btn-success px-4" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function(){

    /* ========= LOADING SUBMIT ========= */
    const form = document.getElementById("editForm");
    const loadingModal = new bootstrap.Modal(document.getElementById("loadingModal"));

    form.addEventListener("submit", function(e){

        e.preventDefault();

        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        loadingModal.show();

        setTimeout(() => {
            form.submit();
        }, 150);
    });

    /* ========= SUCCESS AFTER REDIRECT ========= */
    const successInput = document.getElementById("success-message");

    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(() => {
            modal.show();
        }, 250);
    }

});
</script>

@endsection
