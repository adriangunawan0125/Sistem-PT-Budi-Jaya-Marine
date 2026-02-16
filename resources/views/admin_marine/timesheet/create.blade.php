@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Buat Timesheet</h4>

<form action="{{ route('timesheet.store') }}" method="POST">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

{{-- INFO PO --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row">
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
    <div class="col-md-6">
        <label>Project</label>
        <input type="text" name="project" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Manpower</label>
        <input type="text" name="manpower" class="form-control" required>
    </div>
</div>

</div>
</div>

{{-- ITEM TABLE --}}
<div class="card shadow-sm">
<div class="card-body">

<h5>Timesheet Items</h5>

<div id="items-wrapper">

<div class="item-row border rounded p-3 mb-3">

    <div class="row mb-2">
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" name="items[0][work_date]" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label>Start Time</label>
            <input type="time" name="items[0][time_start]" class="form-control time-start" required>
        </div>

        <div class="col-md-3">
            <label>End Time</label>
            <input type="time" name="items[0][time_end]" class="form-control time-end" required>
        </div>

        <div class="col-md-3">
            <label>Hours</label>
            <input type="text" class="form-control hours bg-light" readonly>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <label>Manpower</label>
            <input type="text" name="items[0][manpower]" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label>Kind of Work</label>
            <textarea name="items[0][kind_of_work]"
                      class="form-control"
                      rows="3"
                      placeholder="Deskripsi pekerjaan..."
                      required></textarea>
        </div>
    </div>

    <div class="text-end mt-2">
        <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
    </div>

</div>

</div>

<button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addRow()">
    + Tambah Row
</button>

</div>
</div>

<button class="btn btn-success mt-3">Simpan Timesheet</button>

</form>

</div>

<script>
let index = 1;

function addRow() {

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-3">

            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date" name="items[${index}][work_date]" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Start Time</label>
                    <input type="time" name="items[${index}][time_start]" class="form-control time-start" required>
                </div>

                <div class="col-md-3">
                    <label>End Time</label>
                    <input type="time" name="items[${index}][time_end]" class="form-control time-end" required>
                </div>

                <div class="col-md-3">
                    <label>Hours</label>
                    <input type="text" class="form-control hours bg-light" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label>Manpower</label>
                    <input type="text" name="items[${index}][manpower]" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label>Kind of Work</label>
                    <textarea name="items[${index}][kind_of_work]"
                              class="form-control"
                              rows="3"
                              placeholder="Deskripsi pekerjaan..."
                              required></textarea>
                </div>
            </div>

            <div class="text-end mt-2">
                <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
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
