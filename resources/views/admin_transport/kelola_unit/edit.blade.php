@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Unit</h4>

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

    <form id="editForm" action="/admin-transport/unit/update/{{ $unit->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Unit</label>
            <input type="text"
                   name="nama_unit"
                   class="form-control @error('nama_unit') is-invalid @enderror"
                   value="{{ old('nama_unit', $unit->nama_unit) }}"
                   required>

            @error('nama_unit')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label>Merek</label>
            <input type="text"
                   name="merek"
                   class="form-control @error('merek') is-invalid @enderror"
                   value="{{ old('merek', $unit->merek) }}"
                   required>

            @error('merek')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- STATUS --}}
        <div class="mb-3">
            <label>Status</label>
            <select name="status"
                    class="form-control @error('status') is-invalid @enderror"
                    required>
                <option value="tersedia" {{ old('status', $unit->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewakan" {{ old('status', $unit->status) == 'disewakan' ? 'selected' : '' }}>Disewakan</option>
            </select>

            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- STNK --}}
        <div class="mb-3">
            <label>Masa Berlaku STNK</label>
            @php
                $stnkDate = old('stnk_expired_at') ?? $unit->stnk_expired_at;
                if ($stnkDate) {
                    try {
                        $stnkDate = \Carbon\Carbon::parse($stnkDate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $stnkDate = '';
                    }
                }
            @endphp
            <input type="date"
                   name="stnk_expired_at"
                   class="form-control @error('stnk_expired_at') is-invalid @enderror"
                   value="{{ $stnkDate }}">

            @error('stnk_expired_at')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Kosongkan jika belum diisi</small>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="/admin-transport/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
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
