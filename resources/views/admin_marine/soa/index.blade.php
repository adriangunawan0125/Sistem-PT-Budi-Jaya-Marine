@extends('layouts.app')

@section('content')

<style>
.soa-table th,
.soa-table td{
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

.aksi-wrapper form{
    margin:0;
}

.btn-sm{
    font-size:12px;
    padding:5px 12px;
}
</style>

<div class="container py-4">

{{-- ================= HEADER ================= --}}
<div class="mb-4">
    <h5 class="fw-semibold mb-0">
        Statement of Account (SOA)
    </h5>
</div>

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body py-3 small">

<form method="GET" action="{{ route('soa.index') }}" id="filterForm">

<div class="row g-3 align-items-end">

    {{-- SEARCH --}}
    <div class="col-md-4">
        <label class="form-label small mb-1">Search Debtor</label>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control form-control-sm"
               placeholder="Nama Debtor">
    </div>

    {{-- BULAN --}}
    <div class="col-md-2">
        <select name="month"
                class="form-control form-control-sm filter-control">
            <option value="">Semua Bulan</option>
            @for($m=1;$m<=12;$m++)
                <option value="{{ $m }}"
                    {{ request('month') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
    </div>

    {{-- TAHUN --}}
    <div class="col-md-2">
        <select name="year"
                class="form-control form-control-sm filter-control">
            <option value="">Semua Tahun</option>
            @for($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}"
                    {{ request('year') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </div>

    {{-- BUTTON GROUP --}}
    <div class="col-md-4">
        <div class="d-flex gap-2 justify-content-end">

            <button type="submit"
                    class="btn btn-primary btn-sm px-3">
                Filter
            </button>

            <a href="{{ route('soa.index') }}"
               class="btn btn-secondary btn-sm" style="margin-left: 4px">
                Reset
            </a>

            <a href="{{ route('soa.create') }}"
               class="btn btn-success btn-sm px-3" style="margin-left: 10px">
                + Buat SOA
            </a>

        </div>
    </div>

</div>

</form>

</div>
</div>


{{-- ================= TABLE ================= --}}
<div class="card shadow-sm">
<div class="card-body p-0">

<div class="table-responsive">
<table class="table table-bordered table-hover align-middle soa-table mb-0">

<thead class="table-light text-center">
<tr>
<th width="50">No</th>
<th>Debtor</th>
<th width="140">Statement Date</th>
<th width="120">Termin</th>
<th width="160" class="text-end">Total</th>
<th width="200">Action</th>
</tr>
</thead>

<tbody>

@forelse($soas as $index => $soa)

@php
$totalInvoice = $soa->items->sum(function($item){
    return $item->invoice->grand_total ?? 0;
});
@endphp

<tr>

<td class="text-center">
    {{ $soas->firstItem() + $index }}
</td>

<td>
    <div class="fw-semibold">
        {{ $soa->debtor }}
    </div>
    <div class="text-muted small">
        {{ $soa->address }}
    </div>
</td>

<td class="text-center">
    {{ \Carbon\Carbon::parse($soa->statement_date)->format('d M Y') }}
</td>

<td class="text-center">
    {{ $soa->termin }}
</td>

<td class="text-end fw-semibold">
    Rp {{ number_format($totalInvoice,0,',','.') }}
</td>

<td>
<div class="aksi-wrapper">

    <a href="{{ route('soa.show', $soa->id) }}"
       class="btn btn-info btn-sm btnDetail">
        Detail
    </a>

    <a href="{{ route('soa.edit', $soa->id) }}"
       class="btn btn-warning btn-sm">
        Edit
    </a>

  <form action="{{ route('soa.destroy', $soa->id) }}"
      method="POST"
      class="deleteForm">
    @csrf
    @method('DELETE')
    <button type="button"
            class="btn btn-danger btn-sm btnDelete"
            data-id="{{ $soa->id }}">
        Hapus
    </button>
</form>

</div>
</td>

</tr>

@empty
<tr>
<td colspan="6" class="text-center text-muted py-4">
Belum ada data SOA
</td>
</tr>
@endforelse

</tbody>
</table>
</div>

</div>

@if($soas->hasPages())
<div class="card-footer small">
    {{ $soas->withQueryString()->links() }}
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
Memuat Data ...
</div>

</div>
</div>
</div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade"
     id="deleteModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Konfirmasi Hapus</h5>

<div class="text-muted mb-4">
Yakin ingin menghapus SOA ini?
</div>

<div class="d-flex justify-content-center gap-2">

    <button type="button"
            class="btn btn-secondary px-4"
            data-bs-dismiss="modal" style="margin-right: 4px">
        Batal
    </button>

    <button type="button"
            class="btn btn-danger px-4"
            id="confirmDeleteBtn">
        Hapus
    </button>

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

    // ================= DETAIL BUTTON =================
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

    // ================= FILTER SUBMIT =================
    const filterForm = document.getElementById("filterForm");

    if(filterForm){
        filterForm.addEventListener("submit", function(){

            loadingModal.show();

        });
    }

    // ================= DELETE MODAL =================
let deleteForm;

const deleteModal = new bootstrap.Modal(
    document.getElementById("deleteModal")
);

document.querySelectorAll(".btnDelete").forEach(btn => {

    btn.addEventListener("click", function(){

        deleteForm = this.closest("form");

        deleteModal.show();

    });

});

document.getElementById("confirmDeleteBtn")
.addEventListener("click", function(){

    if(deleteForm){
        deleteForm.submit();
    }

});
});
</script>
@endsection
