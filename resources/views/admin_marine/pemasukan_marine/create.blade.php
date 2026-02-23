@extends('layouts.app')

@section('content')

<style>
    .form-control {
        border-radius: 6px;
    }

    .form-label.small {
        font-weight: 500;
    }

    .card-section {
        border: 1px solid #f1f1f1;
    }
</style>

<div class="container">

<h4 class="mb-4">Tambah Pemasukan Marine</h4>

<form id="createForm"
      action="{{ route('pemasukan-marine.store') }}"
      method="POST"
      enctype="multipart/form-data">
@csrf

<div class="card shadow-sm mb-4">
<div class="card-body">

{{-- ================= HEADER ================= --}}
<div class="row mb-3">

    <div class="col-md-4">
        <label class="form-label small">No PO</label>
        <select name="po_masuk_id"
                id="poSelect"
                class="form-control"
                required>
            <option value="">-- Pilih PO --</option>
            @foreach($poMasuk as $po)
                <option value="{{ $po->id }}"
                        data-company="{{ $po->mitra_marine }}"
                        data-vessel="{{ $po->vessel }}">
                    {{ $po->no_po_klien }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Tanggal</label>
        <input type="date"
               name="tanggal"
               class="form-control"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Metode</label>
        <input type="text"
               name="metode"
               class="form-control"
               placeholder="Transfer / Cash / Giro"
               required>
    </div>

</div>

<div class="row mb-3">

    <div class="col-md-6">
        <label class="form-label small">Company</label>
        <input type="text"
               id="companyField"
               class="form-control bg-light"
               readonly>
    </div>

    <div class="col-md-6">
        <label class="form-label small">Vessel</label>
        <input type="text"
               id="vesselField"
               class="form-control bg-light"
               readonly>
    </div>

</div>

<div class="row mb-3">

    <div class="col-md-6">
        <label class="form-label small">Nama Pengirim</label>
        <input type="text"
               name="nama_pengirim"
               class="form-control"
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label small">Nominal</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="text"
                   id="nominalInput"
                   class="form-control"
                   placeholder="0"
                   required>
        </div>
        <input type="hidden" name="nominal" id="nominalHidden">
    </div>

</div>

<div class="row">

    <div class="col-md-12 mb-3">
        <label class="form-label small">Keterangan</label>
        <textarea name="keterangan"
                  rows="3"
                  class="form-control"
                  placeholder="Tambahkan keterangan jika diperlukan"></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label small">Upload Bukti</label>
        <input type="file"
               name="bukti"
               id="buktiInput"
               class="form-control"
               accept="image/*">

        <div class="mt-3">
            <img id="previewImage"
                 src="#"
                 class="img-thumbnail d-none"
                 style="max-height:200px;">
        </div>
    </div>

</div>

</div>
</div>

{{-- ================= BUTTON ================= --}}
<div class="d-flex gap-2 mt-4">

    <a href="{{ route('pemasukan-marine.index') }}"
       class="btn btn-secondary btn-sm px-4" style="margin-right: 4px">
        Kembali
    </a>

    <button type="submit"
            class="btn btn-success btn-sm px-4">
        Simpan
    </button>

</div>

</form>
</div>

{{-- ================= LOADING MODAL ================= --}}
<div class="modal fade"
     id="savingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-success mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Menyimpan Data...
</div>

</div>
</div>
</div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

    // AUTO FILL
    document.getElementById('poSelect').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];
        document.getElementById('companyField').value =
            selected.getAttribute('data-company') ?? '';
        document.getElementById('vesselField').value =
            selected.getAttribute('data-vessel') ?? '';
    });

    // FORMAT RUPIAH
    const nominalInput = document.getElementById('nominalInput');
    const nominalHidden = document.getElementById('nominalHidden');

    nominalInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if(value){
            this.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            this.value = '';
        }
        nominalHidden.value = value;
    });

    // PREVIEW IMAGE
    document.getElementById('buktiInput').addEventListener('change', function(event) {
        const input = event.target;
        const preview = document.getElementById('previewImage');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
        }
    });

    // SUBMIT LOADING
    const form = document.getElementById('createForm');

    if(form){
        form.addEventListener('submit', function(){

            if(!form.checkValidity()){
                return;
            }

            const savingModal = new bootstrap.Modal(
                document.getElementById('savingModal')
            );

            savingModal.show();
        });
    }

});
</script>

@endsection