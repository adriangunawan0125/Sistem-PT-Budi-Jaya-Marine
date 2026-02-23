@extends('layouts.app')

@section('content')
<div class="container py-4">

<h4 class="mb-4">Buat Timesheet</h4>

<form action="{{ route('timesheet.store') }}" method="POST" id="createTimesheetForm">
@csrf

<input type="hidden" name="po_masuk_id" value="{{ $poMasuk->id }}">

{{-- ================= INFO PO ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row g-3">
    <div class="col-md-4">
        <small class="text-muted">Company</small>
        <div class="fw-semibold">{{ $poMasuk->mitra_marine }}</div>
    </div>
    <div class="col-md-4">
        <small class="text-muted">Vessel</small>
        <div class="fw-semibold">{{ $poMasuk->vessel }}</div>
    </div>
    <div class="col-md-4">
        <small class="text-muted">PO No</small>
        <div class="fw-semibold">{{ $poMasuk->no_po_klien }}</div>
    </div>
</div>

<hr class="my-4">

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small">Project</label>
        <input type="text" name="project" class="form-control form-control-sm" required>
    </div>

    <div class="col-md-6">
        <label class="form-label small">Manpower</label>
        <input type="text" name="manpower" class="form-control form-control-sm" required>
    </div>
</div>

</div>
</div>

{{-- ================= TIMESHEET ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between align-items-center">
    <strong>Timesheet Items</strong>
    <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()">
        + Tambah Item
    </button>
</div>

<div class="card-body">

<div id="items-wrapper">

<div class="item-row border rounded p-3 mb-3 bg-light">

    <div class="row g-3 mb-2">
        <div class="col-md-3">
            <label class="form-label small">Date</label>
            <input type="date"
                   name="items[0][work_date]"
                   class="form-control form-control-sm"
                   required>
        </div>

        <div class="col-md-3">
            <label class="form-label small">Start Time</label>
            <input type="time"
                   name="items[0][time_start]"
                   class="form-control form-control-sm">
        </div>

        <div class="col-md-3">
            <label class="form-label small">End Time</label>
            <input type="time"
                   name="items[0][time_end]"
                   class="form-control form-control-sm">
        </div>

        <div class="col-md-3">
            <label class="form-label small">Hours</label>
            <input type="number"
                   step="0.01"
                   min="0"
                   max="24"
                   name="items[0][hours]"
                   class="form-control form-control-sm"
                   required>
        </div>
    </div>

    <div class="row g-3 mb-2">
        <div class="col-md-4">
            <label class="form-label small">Manpower</label>
            <input type="text"
                   name="items[0][manpower]"
                   class="form-control form-control-sm">
        </div>
    </div>

    <div class="mb-2">
        <label class="form-label small">Kind of Work</label>
        <textarea name="items[0][kind_of_work]"
                  class="form-control form-control-sm"
                  rows="3"
                  required></textarea>
    </div>

    <div class="text-end">
        <button type="button"
                class="btn btn-danger btn-sm remove-row">
            Hapus
        </button>
    </div>

</div>
</div>

</div>
</div>

<div class="d-flex justify-content gap-2 mt-4">

    <a href="{{ route('po-masuk.show', $poMasuk->id) }}"
       class="btn btn-secondary" style="margin-right: 4px">
        Kembali
    </a>

    <button type="submit" class="btn btn-success">
        Simpan Timesheet
    </button>

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
Menyimpan Timesheet...
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

let index = 1;

function addRow() {

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
    <div class="item-row border rounded p-3 mb-3 bg-light">

        <div class="row g-3 mb-2">
            <div class="col-md-3">
                <label class="form-label small">Date</label>
                <input type="date"
                       name="items[${index}][work_date]"
                       class="form-control form-control-sm"
                       required>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Start Time</label>
                <input type="time"
                       name="items[${index}][time_start]"
                       class="form-control form-control-sm">
            </div>

            <div class="col-md-3">
                <label class="form-label small">End Time</label>
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

        <div class="row g-3 mb-2">
            <div class="col-md-4">
                <label class="form-label small">Manpower</label>
                <input type="text"
                       name="items[${index}][manpower]"
                       class="form-control form-control-sm">
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

                // handle cross midnight
                if(e < s){
                    e.setDate(e.getDate() + 1);
                }

                let diff = (e - s) / (1000 * 60 * 60);
                hours.value = diff.toFixed(2);
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

<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("createTimesheetForm");
    if(!form) return;

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    const warningModal = new bootstrap.Modal(
        document.getElementById("warningModal")
    );

    form.addEventListener("submit", function(e){

        e.preventDefault();

        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        const items = document.querySelectorAll(".item-row");

        if(items.length === 0){
            warningModal.show();
            return;
        }

        loadingModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 400);

    });

});
</script>

@endsection
