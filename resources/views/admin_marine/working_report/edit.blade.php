@extends('layouts.app')

@section('content')
<div class="container py-4">

<h5 class="mb-4 fw-semibold">Edit Working Report</h5>

<form action="{{ route('working-report.update', $workingReport->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
@csrf
@method('PUT')

<input type="hidden" name="po_masuk_id" value="{{ $workingReport->po_masuk_id }}">

{{-- ================= INFO ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body small">

<div class="row g-3">
    <div class="col-md-4">
        <div class="text-muted">Company</div>
        <div class="fw-semibold">{{ $workingReport->poMasuk->mitra_marine }}</div>
    </div>
    <div class="col-md-4">
        <div class="text-muted">Vessel</div>
        <div class="fw-semibold">{{ $workingReport->poMasuk->vessel }}</div>
    </div>
    <div class="col-md-4">
        <div class="text-muted">PO No</div>
        <div class="fw-semibold">{{ $workingReport->poMasuk->no_po_klien }}</div>
    </div>
</div>

<hr class="my-3">

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label small">Project</label>
        <input type="text" 
               name="project" 
               value="{{ $workingReport->project }}"
               class="form-control form-control-sm" 
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Place</label>
        <input type="text" 
               name="place" 
               value="{{ $workingReport->place }}"
               class="form-control form-control-sm">
    </div>

    <div class="col-md-4">
        <label class="form-label small">Periode</label>
        <input type="text" 
               name="periode" 
               value="{{ $workingReport->periode }}"
               class="form-control form-control-sm" 
               required>
    </div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between align-items-center small">
    <strong>Working Report Items</strong>
    <button type="button"
            class="btn btn-primary btn-sm"
            onclick="addItem()">
        + Add Item
    </button>
</div>

<div class="card-body">

<div id="items-wrapper">

@foreach($workingReport->items as $i => $item)

<div class="item-row border rounded p-3 mb-4 bg-light small">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <strong>Item {{ $i+1 }}</strong>
        <button type="button" 
                class="btn btn-sm btn-danger remove-item">
            Remove
        </button>
    </div>

    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">

    <div class="mb-3">
        <label class="form-label small">Date</label>
        <input type="date"
               name="items[{{ $i }}][work_date]"
               value="{{ \Carbon\Carbon::parse($item->work_date)->format('Y-m-d') }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label small">Detail</label>
        <textarea name="items[{{ $i }}][detail]"
                  class="form-control form-control-sm"
                  rows="3"
                  required>{{ $item->detail }}</textarea>
    </div>

    {{-- EXISTING IMAGES --}}
    @if($item->images->count())
    <div class="mb-3">
        <label class="form-label small">Existing Images</label>
        <div class="row">
            @foreach($item->images as $image)
            <div class="col-md-3 mb-3 text-center">
                <img src="{{ asset('storage/'.$image->image_path) }}"
                     class="img-fluid rounded border mb-2"
                     style="max-height:100px;">
                <div class="form-check small">
                    <input type="checkbox"
                           name="delete_images[]"
                           value="{{ $image->id }}"
                           class="form-check-input">
                    <label class="form-check-label">Delete</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- NEW IMAGES --}}
    <div class="image-area">
        <label class="form-label small">Add Images</label>

       <div class="image-input-wrapper">

    <div class="image-input-block mb-2 d-flex gap-2 align-items-center"
         data-input-id="existing-{{ $i }}-0">

        <input type="file"
               name="items[{{ $i }}][images][]"
               class="form-control form-control-sm image-input"
               data-preview-id="existing-{{ $i }}-0"
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
                class="btn btn-sm btn-secondary add-image-btn">
            + Add Image
        </button>
    </div>

</div>

@endforeach

</div>

</div>
</div>

<div class="mt-4 text-end">
    <button class="btn btn-success btn-sm px-4">
        Update Working Report
    </button>
    <a href="{{ route('working-report.show', $workingReport->id) }}" class="btn btn-sm btn-secondary" style="margin-left: 4px">
        Kembali</a>
</div>

</form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>

let index = {{ $workingReport->items->count() }};
let imageInputCounter = 1000; // start tinggi agar tidak bentrok existing

function generateImageInput(itemIndex){

    const uniqueId = 'new-img-' + imageInputCounter++;

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
                <label class="form-label small">Add Images</label>

                <div class="image-input-wrapper">
                    ${generateImageInput(index)}
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

    // REMOVE IMAGE INPUT + PREVIEW
    if(e.target.classList.contains('remove-image')){

        const block = e.target.closest('.image-input-block');
        const previewId = block.getAttribute('data-input-id');
        const itemRow = block.closest('.item-row');
        const previewContainer = itemRow.querySelector('.image-preview');

        previewContainer
            .querySelectorAll(`[data-preview-id="${previewId}"]`)
            .forEach(img => img.remove());

        block.remove();
    }

});


// ================= PREVIEW =================
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
@endsection
