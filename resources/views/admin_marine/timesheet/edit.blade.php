@extends('layouts.app')

@section('content')
<div class="container py-4">

<h5 class="mb-4 fw-semibold">Edit Timesheet</h5>

<form action="{{ route('timesheet.update', $timesheet->id) }}" method="POST">
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
            class="btn btn-outline-primary btn-sm"
            onclick="addRow()">
        + Add Item
    </button>
</div>

<div class="card-body">

<div id="items-wrapper">

@foreach($timesheet->items as $i => $item)
<div class="item-row border rounded p-3 mb-3 bg-light small">

    <div class="row g-3 mb-2">

        <div class="col-md-3">
            <label class="form-label small">Date</label>
            <input type="date"
                   name="items[{{ $i }}][work_date]"
                   value="{{ \Carbon\Carbon::parse($item->work_date)->format('Y-m-d') }}"
                   class="form-control form-control-sm"
                   required>
        </div>

        <div class="col-md-3">
            <label class="form-label small">Start</label>
            <input type="time"
                   name="items[{{ $i }}][time_start]"
                   value="{{ \Carbon\Carbon::parse($item->time_start)->format('H:i') }}"
                   class="form-control form-control-sm time-start"
                   required>
        </div>

        <div class="col-md-3">
            <label class="form-label small">End</label>
            <input type="time"
                   name="items[{{ $i }}][time_end]"
                   value="{{ \Carbon\Carbon::parse($item->time_end)->format('H:i') }}"
                   class="form-control form-control-sm time-end"
                   required>
        </div>

        <div class="col-md-3">
            <label class="form-label small">Hours</label>
            <input type="text"
                   class="form-control form-control-sm hours bg-white"
                   value="{{ number_format($item->hours,2) }}"
                   readonly>
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
                class="btn btn-sm btn-outline-danger remove-row">
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
                    <input type="date" name="items[${index}][work_date]"
                           class="form-control form-control-sm" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Start</label>
                    <input type="time" name="items[${index}][time_start]"
                           class="form-control form-control-sm time-start" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small">End</label>
                    <input type="time" name="items[${index}][time_end]"
                           class="form-control form-control-sm time-end" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Hours</label>
                    <input type="text"
                           class="form-control form-control-sm hours bg-white"
                           readonly>
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

document.addEventListener('input', function(e){

    if(e.target.classList.contains('time-start') || 
       e.target.classList.contains('time-end')){

        const row = e.target.closest('.item-row');
        const start = row.querySelector('.time-start').value;
        const end   = row.querySelector('.time-end').value;
        const hours = row.querySelector('.hours');

        if(start && end){

            let s = new Date("1970-01-01T" + start + ":00");
            let eTime = new Date("1970-01-01T" + end + ":00");

            if(eTime < s){
                eTime.setDate(eTime.getDate() + 1);
            }

            let diff = (eTime - s) / (1000 * 60 * 60);
            hours.value = diff.toFixed(2);
        }
    }
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove-row')){
        e.target.closest('.item-row').remove();
    }
});

</script>

@endsection
