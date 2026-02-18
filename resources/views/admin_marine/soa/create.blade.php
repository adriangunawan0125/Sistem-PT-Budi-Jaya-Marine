@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Buat Statement of Account (SOA)</h4>

<form action="{{ route('soa.store') }}" method="POST">
@csrf

{{-- ================= HEADER ================= --}}
<div class="card mb-4 shadow-sm">
<div class="card-body">

<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Debtor</label>
        <input type="text" name="debtor" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control">
    </div>

    <div class="col-md-4">
        <label class="form-label">Statement Date</label>
        <input type="date" name="statement_date" id="statement_date" class="form-control" required>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-label">Termin</label>
        <input type="text" name="termin" class="form-control" placeholder="Contoh: 30 Days">
    </div>
</div>

</div>
</div>

{{-- ================= ITEMS ================= --}}
<div class="card shadow-sm">
<div class="card-body">

<h5 class="mb-3">SOA Items</h5>

<div id="items-wrapper"></div>

<button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="addItem()">
    + Tambah Invoice
</button>

</div>
</div>

<button class="btn btn-success mt-3">
    Simpan SOA
</button>

</form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>

let index = 0;

/* ================= TAMBAH ITEM ================= */
function addItem(){

    let wrapper = document.getElementById('items-wrapper');

    wrapper.insertAdjacentHTML('beforeend', `
        <div class="item-row border rounded p-3 mb-3">

            <div class="row mb-3">

                <div class="col-md-4">
                    <label class="form-label">Pilih Invoice</label>
                    <select name="items[${index}][invoice_po_id]" 
                            class="form-select invoice-select"
                            required>
                        <option value="">-- Pilih Invoice --</option>
                        @foreach($invoiceList as $inv)
                            <option value="{{ $inv->id }}"
                                data-date="{{ $inv->tanggal_invoice }}"
                                data-total="{{ $inv->grand_total }}">
                                {{ $inv->no_invoice }}
                                ({{ $inv->poMasuk->no_po_klien ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Invoice Date</label>
                    <input type="text" class="form-control invoice-date bg-light" readonly>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Pending Payment</label>
                    <input type="text" class="form-control pending-payment bg-light" readonly>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Acceptment Date</label>
                    <input type="date" 
                           name="items[${index}][acceptment_date]"
                           class="form-control acceptment-date"
                           required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Days</label>
                    <input type="text" class="form-control days bg-light" readonly>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Job Details</label>
                    <textarea name="items[${index}][job_details]" 
                              class="form-control" 
                              rows="2"
                              placeholder="Isi job details (boleh panjang)..."></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                    <label class="form-label">Remarks</label>
                    <input type="text" 
                           name="items[${index}][remarks]"
                           class="form-control">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" 
                            class="btn btn-danger btn-sm w-100 remove-item">
                        Hapus
                    </button>
                </div>
            </div>

        </div>
    `);

    index++;
}

/* ================= GLOBAL EVENT LISTENER ================= */
document.addEventListener('change', function(e){

    // Invoice change
    if(e.target.classList.contains('invoice-select')){

        let option = e.target.options[e.target.selectedIndex];
        let row = e.target.closest('.item-row');

        row.querySelector('.invoice-date').value = option.dataset.date ?? '';

        let total = option.dataset.total ?? 0;
        row.querySelector('.pending-payment').value = formatRupiah(total);
    }

    // Acceptment date change
    if(e.target.classList.contains('acceptment-date')){
        calculateDays(e.target);
    }
});

/* ================= REMOVE ================= */
document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove-item')){
        e.target.closest('.item-row').remove();
    }
});

/* ================= HITUNG DAYS ================= */
function calculateDays(input){

    let row = input.closest('.item-row');
    let statementDate = document.getElementById('statement_date').value;

    if(statementDate && input.value){

        let s = new Date(statementDate);
        let a = new Date(input.value);

        let diff = Math.floor((s - a) / (1000 * 60 * 60 * 24));

        row.querySelector('.days').value = diff >= 0 ? diff : 0;
    }
}

/* ================= FORMAT RUPIAH ================= */
function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}

addItem(); // auto 1 row saat load

</script>

@endsection
