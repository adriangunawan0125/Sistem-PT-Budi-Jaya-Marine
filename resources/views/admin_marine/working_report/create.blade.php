@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Buat Working Report</h4>

<form action="{{ route('working-report.store') }}" 
      method="POST" 
      enctype="multipart/form-data">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

{{-- ================= INFO PO ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row mb-3">
    <div class="col-md-4">
        <strong>Company :</strong><br>
        {{ $poMasuk->mitra_marine }}
    </div>

    <div class="col-md-4">
        <strong>Vessel :</strong><br>
        {{ $poMasuk->vessel }}
    </div>

    <div class="col-md-4">
        <strong>PO No :</strong><br>
        {{ $poMasuk->no_po_klien }}
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-4">
        <label>Project</label>
        <input type="text" name="project" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label>Place</label>
        <input type="text" name="place" class="form-control">
    </div>

    <div class="col-md-4">
        <label>Periode</label>
        <input type="text" name="periode" class="form-control" required>
    </div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-body">

<h5>Working Report Items</h5>

<div id="items-wrapper">

{{-- DEFAULT ITEM --}}
<div class="item-row border rounded p-3 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Item 1</h6>

        <button type="button" 
                class="btn btn-danger btn-sm remove-item"
                style="display:none;">
            Hapus Item
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <label>Date</label>
            <input type="date"
                   name="items[0][work_date]"
                   class="form-control"
                   required>
        </div>
    </div>

    <div class="mb-3">
        <label>Detail</label>
        <textarea name="items[0][detail]"
                  class="form-control"
                  rows="4"
                  required></textarea>
    </div>

    <div class="image-area">
        <label>Upload Images</label>

        <div class="image-input-wrapper mb-2">
            <input type="file"
                   name="items[0][images][]"
                   class="form-control">
        </div>

        <button type="button"
                class="btn btn-outline-secondary btn-sm add-image-btn">
            + Tambah Gambar
        </button>
    </div>

</div>

</div>

<button type="button" 
        class="btn btn-secondary btn-sm mt-2"
        onclick="addItem()">
    + Tambah Item
</button>

</div>
</div>

<button class="btn btn-success mt-3">
    Simpan Working Report
</button>

</form>

</div>

<script>

let index = 1;

/* ================= TAMBAH ITEM ================= */
function addItem() {

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Item ${index + 1}</h6>

                <button type="button" 
                        class="btn btn-danger btn-sm remove-item">
                    Hapus Item
                </button>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date"
                           name="items[${index}][work_date]"
                           class="form-control"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label>Detail</label>
                <textarea name="items[${index}][detail]"
                          class="form-control"
                          rows="4"
                          required></textarea>
            </div>

            <div class="image-area">
                <label>Upload Images</label>

                <div class="image-input-wrapper mb-2">
                    <input type="file"
                           name="items[${index}][images][]"
                           class="form-control">
                </div>

                <button type="button"
                        class="btn btn-outline-secondary btn-sm add-image-btn">
                    + Tambah Gambar
                </button>
            </div>

        </div>
    `);

    index++;
    attachEvents();
}

/* ================= EVENTS ================= */
function attachEvents() {

    // REMOVE ITEM
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.onclick = function() {
            this.closest('.item-row').remove();
        }
    });

    // ADD IMAGE
    document.querySelectorAll('.add-image-btn').forEach(btn => {
        btn.onclick = function() {

            let wrapper = this.closest('.image-area')
                              .querySelector('.image-input-wrapper');

            let itemRow = this.closest('.item-row');
            let dateInput = itemRow.querySelector('input[type="date"]');
            let itemIndex = dateInput.name.match(/items\[(\d+)\]/)[1];

            wrapper.insertAdjacentHTML('beforeend', `
                <div class="mb-2 d-flex gap-2 align-items-center">
                    <input type="file"
                           name="items[${itemIndex}][images][]"
                           class="form-control">

                    <button type="button"
                            class="btn btn-danger btn-sm remove-image">
                        X
                    </button>
                </div>
            `);

            attachEvents();
        }
    });

    // REMOVE IMAGE
    document.querySelectorAll('.remove-image').forEach(btn => {
        btn.onclick = function() {
            this.closest('div').remove();
        }
    });
}

attachEvents();

</script>

@endsection
