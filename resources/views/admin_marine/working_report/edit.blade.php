@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Working Report</h4>

<form action="{{ route('working-report.update', $workingReport->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
@csrf
@method('PUT')

<input type="hidden" name="po_masuk_id" value="{{ $workingReport->po_masuk_id }}">

{{-- ================= INFO PO ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row mb-3">
    <div class="col-md-4">
        <strong>Company :</strong><br>
        {{ $workingReport->poMasuk->mitra_marine }}
    </div>

    <div class="col-md-4">
        <strong>Vessel :</strong><br>
        {{ $workingReport->poMasuk->vessel }}
    </div>

    <div class="col-md-4">
        <strong>PO No :</strong><br>
        {{ $workingReport->poMasuk->no_po_klien }}
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-4">
        <label>Project</label>
        <input type="text" 
               name="project" 
               value="{{ $workingReport->project }}"
               class="form-control" 
               required>
    </div>

    <div class="col-md-4">
        <label>Place</label>
        <input type="text" 
               name="place" 
               value="{{ $workingReport->place }}"
               class="form-control">
    </div>

    <div class="col-md-4">
        <label>Periode</label>
        <input type="text" 
               name="periode" 
               value="{{ $workingReport->periode }}"
               class="form-control" 
               required>
    </div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-body">

<h5>Working Report Items</h5>

<div id="items-wrapper">

@foreach($workingReport->items as $i => $item)

<div class="item-row border rounded p-3 mb-4">

    {{-- HEADER ITEM --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Item {{ $i+1 }}</h6>

        <button type="button" 
                class="btn btn-danger btn-sm remove-item">
            Hapus Item
        </button>
    </div>

    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">

    <div class="row mb-3">
        <div class="col-md-3">
            <label>Date</label>
            <input type="date"
                   name="items[{{ $i }}][work_date]"
                   value="{{ \Carbon\Carbon::parse($item->work_date)->format('Y-m-d') }}"
                   class="form-control"
                   required>
        </div>
    </div>

    <div class="mb-3">
        <label>Detail</label>
        <textarea name="items[{{ $i }}][detail]"
                  class="form-control"
                  rows="4"
                  required>{{ $item->detail }}</textarea>
    </div>

    {{-- EXISTING IMAGES --}}
    @if($item->images->count() > 0)
    <div class="mb-3">
        <label>Existing Images</label>
        <div class="row">
            @foreach($item->images as $image)
            <div class="col-md-3 mb-3 text-center">
                <img src="{{ asset('storage/'.$image->image_path) }}"
                     class="img-fluid rounded border mb-2"
                     style="max-height:120px;">

                <div class="form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           name="delete_images[]"
                           value="{{ $image->id }}">
                    <label class="form-check-label">Hapus</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- NEW IMAGE AREA --}}
    <div class="image-area">
        <label>Upload Images</label>

        <div class="image-input-wrapper mb-2">
            <input type="file"
                   name="items[{{ $i }}][images][]"
                   class="form-control">
        </div>

        <button type="button"
                class="btn btn-outline-secondary btn-sm add-image-btn">
            + Tambah Gambar
        </button>
    </div>

</div>

@endforeach

</div>

<button type="button" 
        class="btn btn-secondary btn-sm mt-2"
        onclick="addItem()">
    + Tambah Item
</button>

</div>
</div>

<button class="btn btn-success mt-3">
    Update Working Report
</button>

</form>

</div>

<script>
let index = {{ $workingReport->items->count() }};

function addItem() {

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Item Baru</h6>

                <button type="button" 
                        class="btn btn-danger btn-sm remove-item">
                    Hapus Item
                </button>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date"
                           name="items[\${index}][work_date]"
                           class="form-control"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label>Detail</label>
                <textarea name="items[\${index}][detail]"
                          class="form-control"
                          rows="4"
                          required></textarea>
            </div>

            <div class="image-area">
                <label>Upload Images</label>

                <div class="image-input-wrapper mb-2">
                    <input type="file"
                           name="items[\${index}][images][]"
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

            let newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.className = 'form-control mb-2';

            let nameBase = this.closest('.item-row')
                               .querySelector('input[type="date"]')
                               .name.replace('[work_date]', '[images][]');

            newInput.name = nameBase;

            wrapper.appendChild(newInput);
        }
    });
}

attachEvents();
</script>

@endsection
