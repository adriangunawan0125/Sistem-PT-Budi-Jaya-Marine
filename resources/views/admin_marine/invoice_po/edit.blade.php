@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Edit Invoice</h4>

<form action="{{ route('invoice-po.update',$invoicePo->id) }}" method="POST"  id="updateInvoiceForm">
@csrf
@method('PUT')

<div class="card shadow-sm mb-4">
<div class="card-body">

{{-- ================= HEADER ================= --}}
<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label class="form-label small">No Invoice</label>
        <input type="text"
               name="no_invoice"
               value="{{ $invoicePo->no_invoice }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Tanggal</label>
        <input type="date"
               name="tanggal_invoice"
               value="{{ $invoicePo->tanggal_invoice }}"
               class="form-control form-control-sm"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label small">Periode</label>
        <input type="text"
               name="periode"
               value="{{ $invoicePo->periode }}"
               class="form-control form-control-sm">
    </div>

</div>

<div class="row g-3 mb-3">

    <div class="col-md-6">
        <label class="form-label small">Authorization No</label>
        <input type="text"
               name="authorization_no"
               value="{{ $invoicePo->authorization_no }}"
               class="form-control form-control-sm">
    </div>

    <div class="col-md-6">
        <label class="form-label small">Manpower</label>
        <input type="text"
               name="manpower"
               value="{{ $invoicePo->manpower }}"
               class="form-control form-control-sm">
    </div>

</div>

<hr>


{{-- ================= ITEMS ================= --}}
<h6 class="mb-3">Item Invoice</h6>

<div id="items-wrapper">

@foreach($invoicePo->items as $i => $item)
<div class="card mb-3 item-row shadow-sm">
<div class="card-body">

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label class="form-label small">Description</label>
        <textarea name="items[{{ $i }}][description]"
                  class="form-control"
                  rows="3"
                  placeholder="Deskripsi item...">{{ $item->description }}</textarea>
    </div>

    {{-- DETAIL ROW --}}
    <div class="row g-3 align-items-end">

        <div class="col-md-2">
            <label class="form-label small">Qty</label>
            <input type="number"
                   step="0.01"
                   name="items[{{ $i }}][qty]"
                  value="{{ rtrim(rtrim(number_format($item->qty,2,'.',''),'0'),'.') }}"
                   class="form-control qty">
        </div>

        <div class="col-md-2">
            <label class="form-label small">Unit</label>
            <input type="text"
                   name="items[{{ $i }}][unit]"
                   value="{{ $item->unit }}"
                   class="form-control">
        </div>
<div class="col-md-3">
    <label class="form-label small">Price</label>

    <input type="text"
           class="form-control rupiah"
           data-hidden="price_hidden_{{ $i }}"
           placeholder="Rp 0"
           value="Rp {{ number_format($item->price,0,',','.') }}">

    <input type="hidden"
           name="items[{{ $i }}][price]"
           value="{{ $item->price }}"
           id="price_hidden_{{ $i }}"
           class="price-hidden">
</div>
        <div class="col-md-3">
            <label class="form-label small">Amount</label>
            <input type="text"
                   value="{{ number_format($item->qty * $item->price, 0, ',', '.') }}"
                   class="form-control amount bg-light fw-semibold"
                   readonly>
        </div>

        <div class="col-md-2 text-end">
            <button type="button"
                    class="btn btn-danger btn-sm px-3"
                    onclick="removeItem(this)">
                Hapus Item
            </button>
        </div>

    </div>

</div>
</div>
@endforeach

</div>

<button type="button"
        onclick="addItem()"
        class="btn btn-primary btn-sm mb-3">
+ Tambah Item
</button>

<hr class="my-4">

{{-- ================= DISCOUNT ================= --}}
<div class="row g-3 align-items-end">

    <div class="col-md-3">
        <label class="form-label small">Discount Type</label>
        <select name="discount_type"
                class="form-control form-control-sm">
            <option value="">No Discount</option>
            <option value="percent"
                {{ $invoicePo->discount_type == 'percent' ? 'selected' : '' }}>
                Percent (%)
            </option>
            <option value="nominal"
                {{ $invoicePo->discount_type == 'nominal' ? 'selected' : '' }}>
                Nominal (Rp)
            </option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label small">Discount Value</label>
        <input type="number"
               step="0.01"
               name="discount_value"
              value="{{ rtrim(rtrim(number_format($invoicePo->discount_value,2,'.',''),'0'),'.') }}"
               class="form-control form-control-sm">
    </div>

</div>
<hr class="my-4">

<div class="text-end">
    <h5>
        Grand Total: Rp 
        <span id="grandTotalText">0</span>
    </h5>
</div>
</div>
</div>

<div class="d-flex justify-content gap-2 mt-4">

    <a href="{{ route('invoice-po.show', $invoicePo->id) }}"
       class="btn btn-secondary btn-sm px-4" style="margin-right: 4px">
        Kembali
    </a>

    <button type="submit"
            class="btn btn-success btn-sm px-4">
        Update Invoice
    </button>


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
Memperbarui Invoice...
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

<i class="bi bi-exclamation-triangle-fill text-warning"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Peringatan</h5>

<div class="text-muted mb-4">
Minimal harus memiliki 1 item invoice.
</div>

<button class="btn btn-warning px-4"
        data-bs-dismiss="modal">
OK
</button>

</div>
</div>
</div>
</div>
{{-- ================= SCRIPT ================= --}}
<script>

let index = {{ $invoicePo->items->count() }};

/* ================= FORMAT RUPIAH ================= */
function formatRupiah(angka) {
    let number_string = angka.replace(/\D/g, ''),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah ? 'Rp ' + rupiah : '';
}

/* ================= GRAND TOTAL ================= */
function updateGrandTotal() {

    let subtotal = 0;

    document.querySelectorAll('.item-row').forEach(row => {

        let qty = parseFloat(row.querySelector('.qty')?.value) || 0;
        let price = parseFloat(row.querySelector('.price-hidden')?.value) || 0;

        let amountField = row.querySelector('.amount');

        let amount = qty * price;
        subtotal += amount;

        if (amountField) {
            amountField.value = amount.toLocaleString('id-ID');
        }
    });

    let discountType = document.querySelector('[name="discount_type"]').value;
    let discountValue = parseFloat(document.querySelector('[name="discount_value"]').value) || 0;

    let discountAmount = 0;

    if (discountType === 'percent') {
        discountAmount = subtotal * discountValue / 100;
    }

    if (discountType === 'nominal') {
        discountAmount = discountValue;
    }

    let grandTotal = subtotal - discountAmount;
    if (grandTotal < 0) grandTotal = 0;

    document.getElementById('grandTotalText').innerText =
        grandTotal.toLocaleString('id-ID');
}

/* ================= ATTACH EVENTS ================= */
function attachEvents() {

    document.querySelectorAll('.item-row').forEach(row => {

        let qty = row.querySelector('.qty');
        let rupiahInput = row.querySelector('.rupiah');
        let hiddenPrice = row.querySelector('.price-hidden');

        if (qty) {
            qty.oninput = function () {
                updateGrandTotal();
            };
        }

        if (rupiahInput) {
            rupiahInput.addEventListener('input', function () {

                let raw = this.value.replace(/\D/g, '');
                raw = raw.replace(/^0+/, '');

                this.value = raw ? formatRupiah(raw) : '';

                if (hiddenPrice) {
                    hiddenPrice.value = raw || 0;
                }

                updateGrandTotal();
            });
        }

    });

    let discountValue = document.querySelector('[name="discount_value"]');
    let discountType = document.querySelector('[name="discount_type"]');

    if (discountValue) {
        discountValue.addEventListener('input', updateGrandTotal);
    }

    if (discountType) {
        discountType.addEventListener('change', updateGrandTotal);
    }
}

/* ================= ADD ITEM ================= */
function addItem() {

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
    <div class="card mb-3 item-row shadow-sm">
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label small">Description</label>
            <textarea name="items[${index}][description]"
                      class="form-control"
                      rows="3"></textarea>
        </div>

        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label small">Qty</label>
                <input type="number"
                       step="0.01"
                       name="items[${index}][qty]"
                       class="form-control qty">
            </div>

            <div class="col-md-2">
                <label class="form-label small">Unit</label>
                <input type="text"
                       name="items[${index}][unit]"
                       class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Price</label>

                <input type="text"
                       class="form-control rupiah"
                       data-hidden="price_hidden_${index}"
                       placeholder="Rp 0">

                <input type="hidden"
                       name="items[${index}][price]"
                       value="0"
                       id="price_hidden_${index}"
                       class="price-hidden">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Amount</label>
                <input type="text"
                       class="form-control amount bg-light"
                       readonly>
            </div>

            <div class="col-md-2 text-end">
                <button type="button"
                        class="btn btn-danger btn-sm"
                        onclick="removeItem(this)">
                    Hapus
                </button>
            </div>

        </div>

    </div>
    </div>
    `);

    index++;
    attachEvents();
    updateGrandTotal();
}

/* ================= REMOVE ITEM ================= */
function removeItem(btn) {
    btn.closest('.item-row').remove();
    updateGrandTotal();
}

/* ================= INIT ================= */
document.addEventListener("DOMContentLoaded", function () {
    attachEvents();
    updateGrandTotal();
});

</script>
@endsection
