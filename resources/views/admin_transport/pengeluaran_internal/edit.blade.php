@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Internal</h4>

    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    <form id="editForm" method="POST"
          action="{{ route('pengeluaran_internal.update', $pengeluaranInternal->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ $pengeluaranInternal->tanggal }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ $pengeluaranInternal->deskripsi }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal</label>

            <input type="text"
                   class="form-control rupiah"
                   data-hidden="nominal"
                   value="Rp {{ number_format($pengeluaranInternal->nominal, 0, ',', '.') }}"
                   required>

            <input type="hidden"
                   name="nominal"
                   value="{{ $pengeluaranInternal->nominal }}">
        </div>

        {{-- ================= GAMBAR NOTA ================= --}}
        <div class="mb-3">
            <label class="form-label">Gambar Nota</label>

            <div class="mb-2">
                <img id="preview-gambar"
                     src="{{ $pengeluaranInternal->gambar ? asset('storage/'.$pengeluaranInternal->gambar) : '' }}"
                     style="max-height:120px;border:1px solid #ddd;border-radius:6px; {{ $pengeluaranInternal->gambar ? '' : 'display:none;' }}">
            </div>

            <input type="file"
                   name="gambar"
                   id="input-gambar"
                   class="form-control"
                   accept="image/*">
        </div>

        {{-- ================= BUKTI TRANSFER ================= --}}
        <div class="mb-3">
            <label class="form-label">Bukti Transfer</label>

            <div class="mb-2">
                <img id="preview-gambar1"
                     src="{{ $pengeluaranInternal->gambar1 ? asset('storage/'.$pengeluaranInternal->gambar1) : '' }}"
                     style="max-height:120px;border:1px solid #ddd;border-radius:6px; {{ $pengeluaranInternal->gambar1 ? '' : 'display:none;' }}">
            </div>

            <input type="file"
                   name="gambar1"
                   id="input-gambar1"
                   class="form-control"
                   accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('pengeluaran_internal.index') }}"
           class="btn btn-secondary">
            Batal
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

<script>
document.addEventListener("DOMContentLoaded", function(){

    /* ========= REALTIME PREVIEW ========= */

    function setupPreview(inputId, previewId){
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        input.addEventListener("change", function(){

            if(this.files && this.files[0]){
                const reader = new FileReader();

                reader.onload = function(e){
                    preview.src = e.target.result;
                    preview.style.display = "block";
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                preview.src = "";
                preview.style.display = "none";
            }
        });
    }

    setupPreview("input-gambar","preview-gambar");
    setupPreview("input-gambar1","preview-gambar1");


    /* ========= LOADING SUBMIT ========= */

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

});
</script>

{{-- ================= JS RUPIAH ================= --}}
<script>
function formatRupiah(angka) {
    let number_string = angka.replace(/\D/g, ''),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah ? 'Rp ' + rupiah : '';
}

document.querySelectorAll('.rupiah').forEach(el => {
    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');

        this.value = raw ? formatRupiah(raw) : '';

        let hiddenName = this.dataset.hidden;
        document.querySelector(`input[name="${hiddenName}"]`).value = raw || 0;
    });
});
</script>

@endsection