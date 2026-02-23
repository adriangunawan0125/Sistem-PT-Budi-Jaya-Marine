@extends('layouts.app')

@section('content')

<style>
.table-pengeluaran th,
.table-pengeluaran td{
    font-size:13px;
    padding:10px 12px;
    vertical-align:middle;
}

.table-hover tbody tr:hover{
    background-color:#f5f7fa;
}

.filter-control{
    font-size:12px;
    height:32px;
    border-radius:6px;
}

.aksi-wrapper{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:6px;
}

.btn-sm{
    font-size:12px;
    padding:5px 12px;
}
</style>

<div class="container py-4">

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-semibold">Daftar Pengeluaran PO</h5>
</div>

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body py-3 small">

<form method="GET" action="{{ route('pengeluaran-po.index') }} " id="filterForm">

<div class="row g-3 align-items-end">

    <div class="col-md-6">
        <label class="form-label small mb-1">Search</label>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control form-control-sm"
               placeholder="No PO / Company / Vessel">
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary btn-sm px-3">
            Filter
        </button>

        <a href="{{ route('pengeluaran-po.index') }}"
           class="btn btn-secondary btn-sm">
            Reset
        </a>
    </div>

</div>

</form>

</div>
</div>


{{-- ================= TABLE ================= --}}
<div class="card shadow-sm">
<div class="card-body p-0">

<div class="table-responsive">
<table class="table table-bordered table-hover align-middle table-pengeluaran mb-0">

<thead class="table-light text-center">
<tr>
<th width="50">No</th>
<th width="180">No PO</th>
<th>Company</th>
<th>Vessel</th>
<th width="180">jumlah pengeluaran</th>
<th width="180">Total Pengeluaran</th>
<th width="150">Action</th>
</tr>
</thead>

<tbody>

@forelse($poMasuk as $index => $po)

@php
    $totalItem = $po->pengeluaran->count();
    $totalAmount = $po->pengeluaran->sum('amount');
@endphp

<tr>

<td class="text-center">
    {{ $poMasuk->firstItem() + $index }}
</td>

<td class="fw-semibold">
    {{ $po->no_po_klien }}
</td>

<td>
    {{ $po->mitra_marine }}
</td>

<td>
    {{ $po->vessel }}
</td>

<td class="text-center">
    {{ $totalItem }} item
</td>

<td class="text-end fw-semibold">
    Rp {{ number_format($totalAmount,0,',','.') }}
</td>

<td>
<div class="aksi-wrapper">

    <a href="{{ route('po-masuk.show',$po->id) }}"
       class="btn btn-info btn-sm btnDetail">
        Detail
    </a>

</div>
</td>

</tr>

@empty
<tr>
<td colspan="7" class="text-center text-muted py-4">
Belum ada data pengeluaran
</td>
</tr>
@endforelse

</tbody>
</table>
</div>

</div>

@if($poMasuk->hasPages())
<div class="card-footer small">
    {{ $poMasuk->withQueryString()->links() }}
</div>
@endif

</div>

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

<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>

<div class="fw-semibold">
Memuat Data Pengeluaran...
</div>

</div>
</div>
</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    // Loading saat Filter
    const filterForm = document.getElementById("filterForm");

    if(filterForm){
        filterForm.addEventListener("submit", function(){
            loadingModal.show();
        });
    }

    // Loading saat klik Detail
    document.querySelectorAll(".btnDetail").forEach(btn => {

        btn.addEventListener("click", function(e){

            e.preventDefault();

            const url = this.getAttribute("href");

            loadingModal.show();

            setTimeout(function(){
                window.location.href = url;
            }, 250);

        });

    });

});
</script>

@endsection
