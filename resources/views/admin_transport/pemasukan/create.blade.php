@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Pemasukan Transport</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="createForm"
          method="POST"
          enctype="multipart/form-data"
          action="{{ route('pemasukan.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <select name="mitra_id" class="form-control" required>
                <option value="">-- Pilih Mitra --</option>
                @foreach($mitras as $mitra)
                    <option value="{{ $mitra->id }}"
                        {{ old('mitra_id') == $mitra->id ? 'selected' : '' }}>
                        {{ $mitra->nama_mitra }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="setoran">Setoran</option>
                <option value="cicilan">Cicilan</option>
                <option value="deposit">Deposit</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal</label>

            <input type="text"
                   class="form-control rupiah"
                   data-hidden="nominal"
                   placeholder="Rp 0"
                   value="{{ old('nominal') ? 'Rp ' . number_format(old('nominal'), 0, ',', '.') : '' }}">

            <input type="hidden"
                   name="nominal"
                   value="{{ old('nominal', 0) }}">
        </div>

        {{-- ================= GAMBAR NOTA ================= --}}
        <div class="mb-3">
            <label class="form-label">Bukti Tf 1</label>

            <div class="mb-2">
                <img id="preview-gambar"
                     src=""
                     style="max-height:150px;border:1px solid #ddd;border-radius:8px;display:none;">
            </div>

            <input type="file"
                   name="gambar"
                   id="input-gambar"
                   class="form-control"
                   accept="image/*">
        </div>

        {{-- ================= GAMBAR BUKTI TF ================= --}}
        <div class="mb-3">
            <label class="form-label">Bukti TF 2 (jika transfer 2 kali)</label>

            <div class="mb-2">
                <img id="preview-gambar1"
                     src=""
                     style="max-height:150px;border:1px solid #ddd;border-radius:8px;display:none;">
            </div>

            <input type="file"
                   name="gambar1"
                   id="input-gambar1"
                   class="form-control"
                   accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">
            Simpan
        </button>

        <a href="{{ route('pemasukan.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>

<!-- LOADING MODAL -->
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Menyimpan data...</div>
            </div>
        </div>
    </div>
</div>

<script>
/* ================= FORMAT RUPIAH ================= */
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


/* ================= PREVIEW IMAGE FUNCTION ================= */
function bindPreview(inputId, previewId) {

    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!input) return;

    input.addEventListener("change", function(){

        if (this.files && this.files[0]) {

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


/* ================= INIT ================= */
document.addEventListener("DOMContentLoaded", function(){

    bindPreview("input-gambar", "preview-gambar");
    bindPreview("input-gambar1", "preview-gambar1");

    const form = document.getElementById("createForm");
    const modal = new bootstrap.Modal(document.getElementById("loadingModal"));

    form.addEventListener("submit", function(e){

        e.preventDefault();

        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        modal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 200);

    });

});
</script>

@endsection