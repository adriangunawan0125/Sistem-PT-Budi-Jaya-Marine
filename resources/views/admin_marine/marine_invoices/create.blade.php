@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Create Invoice</h4>

    <form action="{{ route('marine-invoices.store') }}" method="POST">
        @csrf

        {{-- ================= HEADER ================= --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Company</label>
                <select name="company_id" class="form-control" required>
                    <option value="">-- pilih --</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Project</label>
                <input type="text"
                       name="project"
                       class="form-control"
                       required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Invoice Date</label>
                <input type="date"
                       name="invoice_date"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-3">
                <label>Period</label>
                <input type="month"
                       name="period"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-6">
                <label>Authorization No</label>
                <input type="text"
                       name="authorization_no"
                       class="form-control"
                       required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Vessel</label>
                <input type="text" name="vessel" class="form-control">
            </div>
            <div class="col-md-4">
                <label>PO No</label>
                <input type="text" name="po_no" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Manpower</label>
                <input type="number" name="manpower" class="form-control">
            </div>
        </div>

        <hr>
        <h5>Invoice Items</h5>

        {{-- ================= ITEMS ================= --}}
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
                <tr>
                    <td><input name="description[]" class="form-control" required></td>
                    <td><input name="qty[]" type="number" class="form-control" required></td>
                    <td><input name="unit[]" class="form-control"></td>
                    <td><input name="price[]" type="number" class="form-control" required></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary mb-3" id="add-row">
            + Add Item
        </button>

        <div class="mb-3">
            <label>DP</label>
            <input type="number" name="dp" class="form-control" value="0">
        </div>

        <button class="btn btn-success">Save Invoice</button>
        <a href="{{ route('marine-invoices.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
document.getElementById('add-row').onclick = function () {
    const row = `
    <tr>
        <td><input name="description[]" class="form-control" required></td>
        <td><input name="qty[]" type="number" class="form-control" required></td>
        <td><input name="unit[]" class="form-control"></td>
        <td><input name="price[]" type="number" class="form-control" required></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
        </td>
    </tr>`;
    document.querySelector('#items-table tbody')
        .insertAdjacentHTML('beforeend', row);
};

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
