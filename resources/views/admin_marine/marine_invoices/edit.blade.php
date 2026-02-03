@extends('layouts.app')

@section('content')
<div class="container">

<form action="{{ route('marine-invoices.update', $marineInvoice->id) }}" method="POST">
@csrf
@method('PUT')

<div class="card mb-3">
    <div class="card-header">Edit Invoice</div>
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Company</label>
                <select name="company_id" class="form-control" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"
                            @selected($company->id == $marineInvoice->company_id)>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Project</label>
                <input type="text"
       name="project"
       value="{{ old('project', $marineInvoice->project) }}"
       class="form-control"
       required>

            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Invoice Date</label>
                <input type="date"
                       name="invoice_date"
                       value="{{ $marineInvoice->invoice_date }}"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-3">
                <label>Period</label>
                <input type="date"
                       name="period"
                       value="{{ \Carbon\Carbon::parse($marineInvoice->period)->format('Y-m-d') }}"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-6">
                <label>Authorization No</label>
                <input type="text"
                       name="authorization_no"
                       value="{{ $marineInvoice->authorization_no }}"
                       class="form-control"
                       required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Vessel</label>
                <input type="text"
                       name="vessel"
                       value="{{ $marineInvoice->vessel }}"
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label>PO No</label>
                <input type="text"
                       name="po_no"
                       value="{{ $marineInvoice->po_no }}"
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label>Manpower</label>
                <input type="number"
                       name="manpower"
                       value="{{ $marineInvoice->manpower }}"
                       class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>DP</label>
            <input type="number"
                   name="dp"
                   value="{{ $marineInvoice->dp }}"
                   class="form-control">
        </div>

    </div>
</div>

{{-- ITEMS --}}
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span>Invoice Items</span>
        <button type="button" class="btn btn-sm btn-success" onclick="addItem()">
            + Tambah Item
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th width="80">Qty</th>
                    <th width="100">Unit</th>
                    <th width="120">Price</th>
                    <th width="80">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marineInvoice->items as $item)
                <tr>
                    <td><input name="description[]" value="{{ $item->description }}" class="form-control" required></td>
                    <td><input name="qty[]" type="number" value="{{ $item->qty }}" class="form-control" required></td>
                    <td><input name="unit[]" value="{{ $item->unit }}" class="form-control"></td>
                    <td><input name="price[]" type="number" value="{{ $item->price }}" class="form-control" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-primary mt-3">Update Invoice</button>
    </div>
</div>

</form>
</div>

<script>
function addItem() {
    const row = `
    <tr>
        <td><input name="description[]" class="form-control" required></td>
        <td><input name="qty[]" type="number" class="form-control" required></td>
        <td><input name="unit[]" class="form-control"></td>
        <td><input name="price[]" type="number" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
    </tr>`;
    document.querySelector('#items-table tbody').insertAdjacentHTML('beforeend', row);
}

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
