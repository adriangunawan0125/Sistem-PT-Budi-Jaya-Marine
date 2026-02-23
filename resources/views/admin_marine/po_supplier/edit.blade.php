@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit PO Supplier</h4>

        <a href="{{ route('po-supplier.show', $poSupplier->id) }}"
           class="btn btn-secondary btn-sm px-3">
            Kembali
        </a>
    </div>


    <form action="{{ route('po-supplier.update', $poSupplier->id) }}" method="POST" id="editForm">
        @csrf
        @method('PUT')

        {{-- ================= INFO SUPPLIER ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-semibold">
                Informasi Supplier
            </div>

            <div class="card-body px-4 py-4">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label small">Nama Perusahaan</label>
                        <input type="text"
                               name="nama_perusahaan"
                               class="form-control form-control-sm"
                               value="{{ $poSupplier->nama_perusahaan }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">No PO Internal</label>
                        <input type="text"
                               name="no_po_internal"
                               class="form-control form-control-sm"
                               value="{{ $poSupplier->no_po_internal }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">Tanggal PO</label>
                        <input type="date"
                               name="tanggal_po"
                               class="form-control form-control-sm"
                               value="{{ $poSupplier->tanggal_po }}"
                               required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small">Alamat</label>
                        <textarea name="alamat"
                                  class="form-control form-control-sm"
                                  rows="2">{{ $poSupplier->alamat }}</textarea>
                    </div>

                </div>

            </div>
        </div>


        {{-- ================= ITEM ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Item PO Supplier</strong>

                <button type="button"
                        class="btn btn-success btn-sm px-3"
                        onclick="addItem()">
                    + Tambah Item
                </button>
            </div>

            <div class="card-body p-0">

                <table class="table table-bordered mb-0 align-middle">
                    <thead class="table-light text-center">
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
                                       class="form-control form-control-sm"
                                       value="{{ $item->item }}"
                                       required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $i }}][price_beli]"
                                       class="form-control form-control-sm price"
                                       value="{{ $item->price_beli }}"
                                       step="0.01"
                                       oninput="calculateRow(this)"
                                       required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[{{ $i }}][qty]"
                                       class="form-control form-control-sm qty"
                                       value="{{ $item->qty }}"
                                       step="0.01"
                                       oninput="calculateRow(this)"
                                       required>
                            </td>

                            <td>
                                <input type="text"
                                       name="items[{{ $i }}][unit]"
                                       class="form-control form-control-sm"
                                       value="{{ $item->unit }}">
                            </td>

                            <td>
                                <input type="number"
                                       class="form-control form-control-sm amount"
                                       value="{{ $item->amount }}"
                                       readonly>
                            </td>

                            <td class="text-center">
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
            <div class="card-header fw-semibold">
                Discount
            </div>

            <div class="card-body px-4 py-4">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label small">Jenis Discount</label>
                        <select name="discount_type"
                                id="discount_type"
                                class="form-control form-control-sm filter-control"
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
                        <label class="form-label small">Nilai Discount</label>
                        <input type="number"
                               name="discount_value"
                               id="discount_value"
                               class="form-control form-control-sm"
                               value="{{ $poSupplier->discount_value }}"
                               step="0.01"
                               oninput="updateTotals()">
                    </div>

                </div>

            </div>
        </div>


        {{-- ================= TOTAL ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-end px-4 py-4">

                <div class="mb-2">
                    <span class="text-muted">Total Beli :</span>
                    <strong>Rp <span id="total_beli">0</span></strong>
                </div>

                <div class="mb-2">
                    <span class="text-muted">Discount :</span>
                    <strong>Rp <span id="discount_amount">0</span></strong>
                </div>

                <hr>

                <h5 class="fw-bold">
                    Grand Total :
                    Rp <span id="grand_total">0</span>
                </h5>

            </div>
        </div>


        <div class="text-end">
            <button type="submit"
                    class="btn btn-primary px-4">
                Update PO Supplier
            </button>
            
        </div>

    </form>
{{-- LOADING MODAL --}}
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Memperbarui PO Supplier...
</div>

</div>
</div>
</div>
</div>
{{-- WARNING MODAL --}}
<div class="modal fade" id="warningModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-circle-fill text-warning"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Peringatan</h5>

<div class="text-muted mb-4">
Minimal harus ada 1 item sebelum melakukan update.
</div>

<button class="btn btn-warning px-4"
        data-bs-dismiss="modal">
OK
</button>

</div>
</div>
</div>
</div>
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
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("editForm");
    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    if(!form) return;

    form.addEventListener("submit", function(e){

        e.preventDefault();

        // Validasi bawaan HTML
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // Minimal 1 item
        const items = document.querySelectorAll('#item-body tr');

        if(items.length === 0){

            const warningModal = new bootstrap.Modal(
                document.getElementById("warningModal")
            );

            warningModal.show();
            return;
        }

        loadingModal.show();

        setTimeout(function(){
            HTMLFormElement.prototype.submit.call(form);
        }, 250);

    });

});
</script>

@endsection
