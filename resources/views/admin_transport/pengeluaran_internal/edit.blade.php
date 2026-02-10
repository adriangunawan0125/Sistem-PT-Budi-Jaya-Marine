@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Internal</h4>

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

        {{-- NOMINAL (RUPIAH – EDIT MODE FIX) --}}
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

        <div class="mb-3">
            <label class="form-label">Gambar (Bukti)</label>

            @if($pengeluaranInternal->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$pengeluaranInternal->gambar) }}"
                         width="120"
                         class="d-block mb-2">
                </div>
            @endif

            <input type="file"
                   name="gambar"
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

{{-- ================= JS RUPIAH (KONSISTEN, NO 02) ================= --}}
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
