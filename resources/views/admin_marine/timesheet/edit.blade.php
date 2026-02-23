@extends('layouts.app')

@section('content')
<div class="container py-4">

<h5 class="mb-4 fw-semibold">Edit Timesheet</h5>

<form action="{{ route('timesheet.update', $timesheet->id) }}" method="POST"  id="updateTimesheetForm">
@csrf
@method('PUT')

{{-- ================= INFO ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body small">

<div class="row g-3">
    <div class="col-md-4">
        <div class="text-muted">Company</div>
        <div class="fw-semibold">{{ $timesheet->poMasuk->mitra_marine }}</div>
    </div>
    <div class="col-md-4">
        <div class="text-muted">Vessel</div>
        <div class="fw-semibold">{{ $timesheet->poMasuk->vessel }}</div>
    </div>
    <div class="col-md-4">
        <div class="text-muted">PO No</div>
        <div class="fw-semibold">{{ $timesheet->poMasuk->no_po_klien }}</div>
    </div>
</div>

<hr class="my-3">

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small">Project</label>
        <input type="text"
               name="project"
               value="{{ $timesheet->project }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label small">Manpower</label>
        <input type="text"
               name="manpower"
               value="{{ $timesheet->manpower }}"
               class="form-control form-control-sm"
               required>
    </div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between align-items-center small">
    <strong>Timesheet Items</strong>
    <button type="button"
            class="btn btn-primary btn-sm"
            onclick="addRow()">
        + Add Item
    </button>
</div>

<div class="card-body">

<div id="items-wrapper">

@foreach($timesheet->items as $i => $item)
<div class="item-row border rounded p-3 mb-3 bg-light small">

    <div class="row g-3 mb-2">

        {{-- DATE --}}
        <div class="col-md-3">
            <label class="form-label small">Date</label>
            <input type="date"
                   name="items[{{ $i }}][work_date]"
                   value="{{ \Carbon\Carbon::parse($item->work_date)->format('Y-m-d') }}"
                   class="form-control form-control-sm"
                   required>
        </div>

        {{-- START --}}
        <div class="col-md-3">
            <label class="form-label small">Start</label>
            <input type="time"
                   name="items[{{ $i }}][time_start]"
                   value="{{ $item->time_start ? \Carbon\Carbon::parse($item->time_start)->format('H:i') : '' }}"
                   class="form-control form-control-sm">
        </div>

        {{-- END --}}
        <div class="col-md-3">
            <label class="form-label small">End</label>
            <input type="time"
                   name="items[{{ $i }}][time_end]"
                   value="{{ $item->time_end ? \Carbon\Carbon::parse($item->time_end)->format('H:i') : '' }}"
                   class="form-control form-control-sm">
        </div>

        {{-- HOURS (MANUAL) --}}
        <div class="col-md-3">
            <label class="form-label small">Hours</label>
            <input type="number"
                   step="0.01"
                   min="0"
                   max="24"
                   name="items[{{ $i }}][hours]"
                   value="{{ number_format($item->hours,2,'.','') }}"
                   class="form-control form-control-sm"
                   required>
        </div>

    </div>

    <div class="mb-2">
        <label class="form-label small">Kind of Work</label>
        <textarea name="items[{{ $i }}][kind_of_work]"
                  class="form-control form-control-sm"
                  rows="3"
                  required>{{ $item->kind_of_work }}</textarea>
    </div>

    <div class="text-end">
        <button type="button"
                class="btn btn-sm btn-danger remove-row">
            Remove
        </button>
    </div>

</div>
@endforeach

</div>

</div>
</div>

<div class="mt-4 text-end">
    <button class="btn btn-success btn-sm px-4">
        Update Timesheet
    </button>
        <a href="{{ route('timesheet.show', $timesheet->id) }}"
            class="btn btn-secondary btn-sm px-3" style="margin-left: 4px">
                Kembali
            </a>
</div>

</form>
</div>
{{-- UPDATE LOADING MODAL --}}
<div class="modal fade"
     id="updateModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">

                <div class="spinner-border text-success mb-3"
                     style="width:3rem;height:3rem;"></div>

                <div class="fw-semibold">
                    Memperbarui Timesheet...
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
                    Minimal harus ada 1 timesheet item.
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

let index = {{ $timesheet->items->count() }};

function addRow(){

    const wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-3 bg-light small">

            <div class="row g-3 mb-2">

                <div class="col-md-3">
                    <label class="form-label small">Date</label>
                    <input type="date"
                           name="items[${index}][work_date]"
                           class="form-control form-control-sm"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Start</label>
                    <input type="time"
                           name="items[${index}][time_start]"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label small">End</label>
                    <input type="time"
                           name="items[${index}][time_end]"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Hours</label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="24"
                           name="items[${index}][hours]"
                           class="form-control form-control-sm"
                           required>
                </div>

            </div>

            <div class="mb-2">
                <label class="form-label small">Kind of Work</label>
                <textarea name="items[${index}][kind_of_work]"
                          class="form-control form-control-sm"
                          rows="3"
                          required></textarea>
            </div>

            <div class="text-end">
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-row">
                    Remove
                </button>
            </div>

        </div>
    `);

    index++;
}


document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove-row')){
        e.target.closest('.item-row').remove();
    }
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("updateTimesheetForm");
    if(!form) return;

    const updateModal = new bootstrap.Modal(
        document.getElementById("updateModal")
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
        updateModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 400);

    });

});
</script>

@endsection
