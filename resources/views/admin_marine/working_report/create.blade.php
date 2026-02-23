@extends('layouts.app')

@section('content')
<div class="container py-4">

<h5 class="mb-4 fw-semibold">Buat Working Report</h5>

<form action="{{ route('working-report.store') }}" 
      method="POST" 
      enctype="multipart/form-data" id="createWorkingReportForm">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

{{-- ================= INFO ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body small">

<div class="row g-3">
    <div class="col-md-4">
        <div class="text-muted">Company</div>
        <div class="fw-semibold">{{ $poMasuk->mitra_marine }}</div>
    </div>
    <div class="col-md-4">
        <div class="text-muted">Vessel</div>
        <div class="fw-semibold">{{ $poMasuk->vessel }}</div>
    </div>
    <div class="col-md-4">
        <div class="text-muted">PO No</div>
        <div class="fw-semibold">{{ $poMasuk->no_po_klien }}</div>
    </div>
</div>

<hr class="my-3">

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label small">Project</label>
        <input type="text" name="project" class="form-control form-control-sm" required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Place</label>
        <input type="text" name="place" class="form-control form-control-sm">
    </div>

    <div class="col-md-4">
        <label class="form-label small">Periode</label>
        <input type="text" name="periode" class="form-control form-control-sm" required>
    </div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between align-items-center small">
    <strong>Working Report Items</strong>
    <button type="button"
            class="btn btn-outline-primary btn-sm"
            onclick="addItem()">
        + Add Item
    </button>
</div>

<div class="card-body">
<div id="items-wrapper">

<div class="item-row border rounded p-3 mb-4 bg-light small">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <strong>Item 1</strong>
    </div>

    <div class="mb-3">
        <label class="form-label small">Date</label>
        <input type="date"
               name="items[0][work_date]"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label small">Detail</label>
        <textarea name="items[0][detail]"
                  class="form-control form-control-sm"
                  rows="3"
                  required></textarea>
    </div>

   <div class="image-area">
    <label class="form-label small">Images</label>

    <div class="image-input-wrapper">
        <div class="image-input-block mb-2 d-flex gap-2 align-items-center"
             data-input-id="img-input-0">

            <input type="file"
                   name="items[0][images][]"
                   class="form-control form-control-sm image-input"
                   data-preview-id="img-input-0"
                   accept="image/*"
                   multiple>

            <button type="button"
                    class="btn btn-sm btn-outline-danger remove-image">
                X
            </button>
        </div>
    </div>

    <div class="image-preview d-flex flex-wrap gap-2 mt-2"></div>

    <button type="button"
            class="btn btn-sm btn-outline-secondary add-image-btn">
        + Add Image
    </button>
</div>

</div>

</div>
</div>
</div>

<div class="mt-4 text-end">
    <button class="btn btn-success btn-sm px-4">
        Simpan Working Report
    </button>
    <a href="{{ route('po-masuk.show', $poMasuk->id) }}" 
       class="btn btn-sm btn-secondary" 
       style="margin-left: 4px">
        Kembali
    </a>
</div>

</form>
</div>
{{-- LOADING MODAL --}}
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-success mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Menyimpan Working Report...
</div>

</div>
</div>
</div>
</div>

{{-- WARNING MODAL --}}
<div class="modal fade"
     id="warningModal"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-warning"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Peringatan</h5>

<div class="text-muted mb-4">
Minimal harus ada 1 item Working Report.
</div>

<button type="button"
        class="btn btn-warning px-4"
        data-bs-dismiss="modal">
    OK
</button>

</div>
</div>
</div>
</div>
{{-- ================= SCRIPT ================= --}}
<script>

let index = 1;
let imageInputCounter = 0;

function addItem(){

    const wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-4 bg-light small">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>Item ${index + 1}</strong>
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-item">
                    Remove
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label small">Date</label>
                <input type="date"
                       name="items[${index}][work_date]"
                       class="form-control form-control-sm"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label small">Detail</label>
                <textarea name="items[${index}][detail]"
                          class="form-control form-control-sm"
                          rows="3"
                          required></textarea>
            </div>

         <div class="image-area">
    <label class="form-label small">Images</label>

    <div class="image-input-wrapper">
        <div class="image-input-block mb-2 d-flex gap-2 align-items-center"
             data-input-id="img-input-0">

            <input type="file"
                   name="items[0][images][]"
                   class="form-control form-control-sm image-input"
                   data-preview-id="img-input-0"
                   accept="image/*"
                   multiple>

            <button type="button"
                    class="btn btn-sm btn-outline-danger remove-image">
                X
            </button>
        </div>
    </div>

    <div class="image-preview d-flex flex-wrap gap-2 mt-2"></div>

    <button type="button"
            class="btn btn-sm btn-outline-secondary add-image-btn">
        + Add Image
    </button>
</div>

        </div>
    `);

    index++;
}

// Generate input with unique ID
function generateImageInput(itemIndex){

    const uniqueId = 'img-input-' + imageInputCounter++;

    return `
        <div class="image-input-block mb-2 d-flex gap-2 align-items-center"
             data-input-id="${uniqueId}">

            <input type="file"
                   name="items[${itemIndex}][images][]"
                   class="form-control form-control-sm image-input"
                   data-preview-id="${uniqueId}"
                   accept="image/*"
                   multiple>

            <button type="button"
                    class="btn btn-sm btn-outline-danger remove-image">
                X
            </button>
        </div>
    `;
}

document.addEventListener('click', function(e){

    // REMOVE ITEM
    if(e.target.classList.contains('remove-item')){
        e.target.closest('.item-row').remove();
    }

    // ADD IMAGE INPUT
    if(e.target.classList.contains('add-image-btn')){

        const itemRow = e.target.closest('.item-row');
        const dateInput = itemRow.querySelector('input[type="date"]');
        const itemIndex = dateInput.name.match(/items\[(\d+)\]/)[1];
        const wrapper = itemRow.querySelector('.image-input-wrapper');

        wrapper.insertAdjacentHTML('beforeend', generateImageInput(itemIndex));
    }

    // REMOVE IMAGE INPUT + RELATED PREVIEW
    if(e.target.classList.contains('remove-image')){

        const block = e.target.closest('.image-input-block');
        const previewId = block.getAttribute('data-input-id');
        const itemRow = block.closest('.item-row');
        const previewContainer = itemRow.querySelector('.image-preview');

        // Remove preview images linked to this input
        previewContainer
            .querySelectorAll(`[data-preview-id="${previewId}"]`)
            .forEach(img => img.remove());

        block.remove();
    }

});

// ================= PREVIEW SYSTEM =================
document.addEventListener('change', function(e){

    if(!e.target.classList.contains('image-input')) return;

    const input = e.target;
    const previewId = input.getAttribute('data-preview-id');
    const itemRow = input.closest('.item-row');
    const previewContainer = itemRow.querySelector('.image-preview');

    if(!input.files) return;

    Array.from(input.files).forEach(file => {

        if(!file.type.startsWith('image/')) return;

        const reader = new FileReader();

        reader.onload = function(ev){

            const img = document.createElement('img');
            img.src = ev.target.result;
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            img.classList.add('img-thumbnail');
            img.setAttribute('data-preview-id', previewId);

            previewContainer.appendChild(img);
        };

        reader.readAsDataURL(file);
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("createWorkingReportForm");
    if(!form) return;

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    const warningModal = new bootstrap.Modal(
        document.getElementById("warningModal")
    );

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // Validasi HTML
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // Minimal 1 item
        const items = document.querySelectorAll(".item-row");

        if(items.length === 0){
            warningModal.show();
            return;
        }

        // Show loading
        loadingModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 500);

    });

});

</script>

@endsection