@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Jaminan Mitra</h4>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- SUCCESS TRIGGER --}}
    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    <form id="editForm"
          action="{{ route('jaminan_mitra.update', $jaminanMitra->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Mitra</label>
            <input type="text"
                   class="form-control"
                   value="{{ $jaminanMitra->mitra->nama_mitra }} - {{ $jaminanMitra->mitra->no_hp }}"
                   disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Jaminan</label>
            <input type="text"
                   name="jaminan"
                   value="{{ old('jaminan', $jaminanMitra->jaminan) }}"
                   class="form-control"
                   required>
        </div>

        @foreach(['gambar_1','gambar_2','gambar_3'] as $g)
        <div class="mb-3">
            <label class="form-label">{{ strtoupper(str_replace('_',' ', $g)) }}</label><br>

            @if($jaminanMitra->$g)
                <img src="{{ asset('storage/'.$jaminanMitra->$g) }}"
                     width="80"
                     class="mb-2 rounded border"><br>
            @endif

            <input type="file" name="{{ $g }}" class="form-control">
        </div>
        @endforeach

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('jaminan_mitra.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static"
     data-bs-keyboard="false" tabindex="-1">
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
                    <i class="bi bi-check-circle-fill text-success"
                       style="font-size:60px;"></i>
                </div>
                <h5 class="fw-bold mb-2">Berhasil</h5>
                <div id="successText" class="text-muted"></div>
                <div class="mt-4">
                    <button class="btn btn-success px-4"
                            data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

    /* LOADING SAAT SUBMIT */
    const form = document.getElementById("editForm");
    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

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

    /* SUCCESS SETELAH REDIRECT */
    const successInput = document.getElementById("success-message");
    if(successInput){
        const modal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(() => {
            modal.show();
        }, 250);
    }

});
</script>

@endsection
