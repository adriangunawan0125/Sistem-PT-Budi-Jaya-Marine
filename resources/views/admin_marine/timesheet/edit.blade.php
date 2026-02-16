@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Timesheet</h4>

<form action="{{ route('timesheet.update', $timesheet->id) }}" method="POST">
@csrf
@method('PUT')

{{-- ================= INFO PO ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row">
    <div class="col-md-4">
        <strong>Company :</strong><br>
        {{ $timesheet->poMasuk->mitra_marine }}
    </div>
    <div class="col-md-4">
        <strong>Vessel :</strong><br>
        {{ $timesheet->poMasuk->vessel }}
    </div>
    <div class="col-md-4">
        <strong>PO No :</strong><br>
        {{ $timesheet->poMasuk->no_po_klien }}
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <label>Project</label>
        <input type="text"
               name="project"
               value="{{ $timesheet->project }}"
               class="form-control"
               required>
    </div>

    <div class="col-md-6">
        <label>Manpower</label>
        <input type="text"
               name="manpower"
               value="{{ $timesheet->manpower }}"
               class="form-control"
               required>
    </div>
</div>

</div>
</div>

{{-- ================= ITEM TABLE ================= --}}
<div class="card shadow-sm">
<div class="card-body">

<h5>Timesheet Items</h5>

<div id="items-wrapper">

@foreach($timesheet->items as $i => $item)
<div class="item-row border rounded p-3 mb-3">

    <div class="row mb-2">
        <div class="col-md-3">
            <label>Date</label>
            <input type="date"
                   name="items[{{ $i }}][work_date]"
                   value="{{ \Carbon\Carbon::parse($item->work_date)->format('Y-m-d') }}"
                   class="form-control"
                   required>
        </div>

        <div class="col-md-3">
            <label>Start Time</label>
            <input type="time"
                   name="items[{{ $i }}][time_start]"
                   value="{{ $item->time_start }}"
                   class="form-control time-start"
                   required>
        </div>

        <div class="col-md-3">
            <label>End Time</label>
            <input type="time"
                   name="items[{{ $i }}][time_end]"
                   value="{{ $item->time_end }}"
                   class="form-control time-end"
                   required>
        </div>

        <div class="col-md-3">
            <label>Hours</label>
            <input type="text"
                   class="form-control hours bg-light"
                   value="{{ $item->hours }}"
                   readonly>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <label>Manpower</label>
            <input type="text"
                   name="items[{{ $i }}][manpower]"
                   value="{{ $item->manpower }}"
                   class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label>Kind of Work</label>
            <textarea name="items[{{ $i }}][kind_of_work]"
                      class="form-control"
                      rows="3"
                      required>{{ $item->kind_of_work }}</textarea>
        </div>
    </div>

    <div class="text-end mt-2">
        <button type="button"
                class="btn btn-danger btn-sm remove-row">
            Hapus
        </button>
    </div>

</div>
@endforeach

</div>

<button type="button"
        class="btn btn-secondary btn-sm mt-2"
        onclick="addRow()">
    + Tambah Row
</button>

</div>
</div>

<button class="btn btn-success mt-3">
    Update Timesheet
</button>

</form>

</div>

<script>
let index = {{ $timesheet->items->count() }};

function addRow() {

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-3">

            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date"
                           name="items[${index}][work_date]"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-3">
                    <label>Start Time</label>
                    <input type="time"
                           name="items[${index}][time_start]"
                           class="form-control time-start"
                           required>
                </div>

                <div class="col-md-3">
                    <label>End Time</label>
                    <input type="time"
                           name="items[${index}][time_end]"
                           class="form-control time-end"
                           required>
                </div>

                <div class="col-md-3">
                    <label>Hours</label>
                    <input type="text"
                           class="form-control hours bg-light"
                           readonly>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label>Manpower</label>
                    <input type="text"
                           name="items[${index}][manpower]"
                           class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label>Kind of Work</label>
                    <textarea name="items[${index}][kind_of_work]"
                              class="form-control"
                              rows="3"
                              required></textarea>
                </div>
            </div>

            <div class="text-end mt-2">
                <button type="button"
                        class="btn btn-danger btn-sm remove-row">
                    Hapus
                </button>
            </div>

        </div>
    `);

    index++;
    attachEvents();
}

function attachEvents() {

    document.querySelectorAll('.item-row').forEach(row => {

        let start = row.querySelector('.time-start');
        let end   = row.querySelector('.time-end');
        let hours = row.querySelector('.hours');

        function calculate() {
            if(start.value && end.value) {
                let s = new Date("1970-01-01T" + start.value + ":00");
                let e = new Date("1970-01-01T" + end.value + ":00");

                let diff = (e - s) / (1000 * 60 * 60);
                hours.value = diff > 0 ? diff.toFixed(2) : 0;
            }
        }

        start?.addEventListener('change', calculate);
        end?.addEventListener('change', calculate);
    });

    document.querySelectorAll('.remove-row').forEach(btn => {
        btn.onclick = function() {
            this.closest('.item-row').remove();
        }
    });
}

attachEvents();
</script>

@endsection
