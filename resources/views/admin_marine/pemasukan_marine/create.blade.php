@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Tambah Pemasukan Marine</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('pemasukan-marine.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row g-3">

                    {{-- PILIH PO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No PO</label>
                        <select name="po_masuk_id"
                                id="poSelect"
                                class="form-control form-control-sm"
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

                    {{-- COMPANY --}}
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <input type="text"
                               id="companyField"
                               class="form-control form-control-sm"
                               readonly>
                    </div>

                    {{-- VESSEL --}}
                    <div class="col-md-3">
                        <label class="form-label">Vessel</label>
                        <input type="text"
                               id="vesselField"
                               class="form-control form-control-sm"
                               readonly>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date"
                               name="tanggal"
                               class="form-control form-control-sm"
                               required>
                    </div>

                    {{-- NAMA PENGIRIM --}}
                    <div class="col-md-4">
                        <label class="form-label">Nama Pengirim</label>
                        <input type="text"
                               name="nama_pengirim"
                               class="form-control form-control-sm"
                               required>
                    </div>

                    {{-- METODE --}}
                    <div class="col-md-4">
                        <label class="form-label">Metode</label>
                        <input type="text"
                               name="metode"
                               class="form-control form-control-sm"
                               placeholder="Transfer / Cash / Giro"
                               required>
                    </div>

                    {{-- NOMINAL --}}
                    <div class="col-md-4">
                        <label class="form-label">Nominal</label>
                        <input type="number"
                               name="nominal"
                               class="form-control form-control-sm"
                               placeholder="Masukkan nominal"
                               min="0"
                               step="0.01"
                               required>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan"
                                  rows="2"
                                  class="form-control form-control-sm"></textarea>
                    </div>

                    {{-- BUKTI --}}
                    <div class="col-md-6">
                        <label class="form-label">Upload Bukti</label>
                        <input type="file"
                               name="bukti"
                               id="buktiInput"
                               class="form-control form-control-sm"
                               accept="image/*">

                        {{-- PREVIEW --}}
                        <div class="mt-3">
                            <img id="previewImage"
                                 src="#"
                                 class="img-thumbnail d-none"
                                 style="max-height: 200px;">
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary btn-sm px-4" style="margin-right: 4px">
                        Simpan
                    </button>

                    <a href="{{ route('pemasukan-marine.index') }}"
                       class="btn btn-secondary btn-sm">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- AUTO FILL COMPANY & VESSEL & PREVIEW IMAGE --}}
<script>
document.getElementById('poSelect').addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];
    document.getElementById('companyField').value =
        selected.getAttribute('data-company') ?? '';
    document.getElementById('vesselField').value =
        selected.getAttribute('data-vessel') ?? '';
});

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
</script>

@endsection