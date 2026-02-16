@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit PO Supplier</h4>

        <a href="{{ route('po-supplier.show', $poSupplier->id) }}"
           class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>


    <form action="{{ route('po-supplier.update', $poSupplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ================= INFO SUPPLIER ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-bold">
                Informasi Supplier
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Perusahaan</label>
                        <input type="text"
                               name="nama_perusahaan"
                               class="form-control"
                               value="{{ $poSupplier->nama_perusahaan }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label>No PO Internal</label>
                        <input type="text"
                               name="no_po_internal"
                               class="form-control"
                               value="{{ $poSupplier->no_po_internal }}"
                               required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Tanggal PO</label>
                        <input type="date"
                               name="tanggal_po"
                               class="form-control"
                               value="{{ $poSupplier->tanggal_po }}"
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat"
                              class="form-control"
                              rows="2">{{ $poSupplier->alamat }}</textarea>
                </div>

            </div>
        </div>


        {{-- ================= ITEM ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Item PO Supplier</strong>

                <button type="button"
                        class="btn btn-success btn-sm"
                        onclick="addItem()">
                    + Tambah Item
                </button>
            </div>

            <div class="card-body p-0">

                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th width="150">Harga Beli</th>
                            <th width="100">Qty</th>
                            <th width="100">Unit</th>
                            <th width="150">Amount</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="item-body">

                        @foreach($poSupplier->items as $i => $item)
                        <tr>
                            <td>
                                <input type="text"
                                       name="items[{{ $i }}][item]"
                                       class="form-control"
                                       value="{{ $item->item }}"
                                       required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $i }}][price_beli]"
                                       class="form-control price"
                                       value="{{ $item->price_beli }}"
                                       step="0.01"
                                       oninput="calculateRow(this)"
                                       required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $i }}][qty]"
                                       class="form-control qty"
                                       value="{{ $item->qty }}"
                                       step="0.01"
                                       oninput="calculateRow(this)"
                                       required>
                            </td>

                            <td>
                                <input type="text"
                                       name="items[{{ $i }}][unit]"
                                       class="form-control"
                                       value="{{ $item->unit }}">
                            </td>

                            <td>
                                <input type="number"
                                       class="form-control amount"
                                       value="{{ $item->amount }}"
                                       readonly>
                            </td>

                            <td>
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="this.closest('tr').remove();updateTotals();">
                                    X
                                </button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>


        {{-- ================= DISCOUNT ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-bold">
                Discount
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-4">
                        <label>Jenis Discount</label>
                        <select name="discount_type"
                                id="discount_type"
                                class="form-control"
                                onchange="updateTotals()">
                            <option value="">Tanpa Discount</option>
                            <option value="percent"
                                {{ $poSupplier->discount_type == 'percent' ? 'selected' : '' }}>
                                Percent (%)
                            </option>
                            <option value="nominal"
                                {{ $poSupplier->discount_type == 'nominal' ? 'selected' : '' }}>
                                Nominal (Rp)
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Nilai Discount</label>
                        <input type="number"
                               name="discount_value"
                               id="discount_value"
                               class="form-control"
                               value="{{ $poSupplier->discount_value }}"
                               step="0.01"
                               oninput="updateTotals()">
                    </div>
                </div>

            </div>
        </div>


        {{-- ================= TOTAL ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-end">

                <h6>Total Beli:
                    Rp <span id="total_beli">0</span>
                </h6>

                <h6>Discount:
                    Rp <span id="discount_amount">0</span>
                </h6>

                <h5 class="fw-bold">
                    Grand Total:
                    Rp <span id="grand_total">0</span>
                </h5>

            </div>
        </div>


        <div class="text-end">
            <button type="submit"
                    class="btn btn-primary">
                Update PO Supplier
            </button>
        </div>

    </form>

</div>


{{-- ================= JAVASCRIPT ================= --}}
<script>

let itemIndex = {{ $poSupplier->items->count() }};

function addItem(){

let tbody = document.getElementById('item-body');

let row = `
<tr>
<td><input type="text" name="items[${itemIndex}][item]" class="form-control" required></td>
<td><input type="number" name="items[${itemIndex}][price_beli]" class="form-control price" step="0.01" oninput="calculateRow(this)" required></td>
<td><input type="number" name="items[${itemIndex}][qty]" class="form-control qty" step="0.01" oninput="calculateRow(this)" required></td>
<td><input type="text" name="items[${itemIndex}][unit]" class="form-control"></td>
<td><input type="number" class="form-control amount" readonly></td>
<td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();updateTotals();">X</button></td>
</tr>`;

tbody.insertAdjacentHTML('beforeend', row);
itemIndex++;
}

function calculateRow(input){

let row = input.closest('tr');
let price = parseFloat(row.querySelector('.price').value) || 0;
let qty   = parseFloat(row.querySelector('.qty').value) || 0;

row.querySelector('.amount').value = price * qty;

updateTotals();
}

function updateTotals(){

let total = 0;

document.querySelectorAll('.amount').forEach(a=>{
total += parseFloat(a.value) || 0;
});

let type = document.getElementById('discount_type').value;
let value = parseFloat(document.getElementById('discount_value').value) || 0;

let discount = 0;

if(type === 'percent'){
discount = (total * value) / 100;
}

if(type === 'nominal'){
discount = value;
}

let grand = total - discount;

document.getElementById('total_beli').innerText = total.toLocaleString('id-ID');
document.getElementById('discount_amount').innerText = discount.toLocaleString('id-ID');
document.getElementById('grand_total').innerText = grand.toLocaleString('id-ID');
}

updateTotals();

</script>

@endsection
