@extends('layouts.app')

@section('content')
<div class="container">
    {{-- SUCCESS --}}
@if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

{{-- ERROR --}}
@if (session('error'))
    <input type="hidden" id="error-message" value="{{ session('error') }}">
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">PO Masuk (Client PO)</h4>
</div>

{{-- FILTER --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body py-3">

        <form method="GET" action="{{ route('po-masuk.index') }}">
            <div class="row align-items-end g-3">

                {{-- SEARCH --}}
                <div class="col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="No PO / Mitra / Vessel">
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
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}"
                                {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- BUTTON --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary btn-sm px-3" style="margin-left: 7px">
                        Filter
                    </button>

                    <a href="{{ route('po-masuk.index') }}"
                       class="btn btn-secondary btn-sm" style="margin-left: 7px">
                        Reset
                    </a>

                    <a href="{{ route('po-masuk.create') }}"
                       class="btn btn-success btn-sm ms-auto" style="margin-left: 7px">
                        + Create PO
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>


<div class="card shadow-sm">
<div class="card-body p-0">

<div class="table-responsive">
<table class="table table-bordered table-hover align-middle po-table mb-0">

<thead class="table-light text-center">
<tr>
<th width="50">No</th>
<th width="170">No PO</th>
<th width="200">Mitra</th>
<th width="160">Vessel</th>
<th width="130">Tanggal</th>
<th width="180">Margin</th>
<th width="160">Status</th>
<th width="180">Action</th>
</tr>
</thead>

<tbody>

@forelse($poMasuk as $index => $po)

@php
    $totalBeli = $po->poSuppliers->sum('grand_total');
    $totalPengeluaran = $po->pengeluaran->sum('amount');
    $margin = $po->total_jual - $totalBeli - $totalPengeluaran;

    if($margin > 0){
        $marginClass = 'text-success';
        $marginLabel = 'Profit';
    } elseif($margin < 0){
        $marginClass = 'text-danger';
        $marginLabel = 'Loss';
    } else {
        $marginClass = 'text-warning';
        $marginLabel = 'Break Even';
    }
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
    {{ \Carbon\Carbon::parse($po->tanggal_po)->format('d-m-Y') }}
</td>

<td class="text-center {{ $marginClass }}">
    <strong>
        Rp {{ number_format($margin,0,',','.') }}
    </strong>
    <div class="small">
        {{ $marginLabel }}
    </div>
</td>

<td class="text-center">
<span class="badge text-light
@if($po->status == 'draft') bg-secondary
@elseif($po->status == 'approved') bg-primary
@elseif($po->status == 'processing') bg-warning text-light
@elseif($po->status == 'delivered') bg-info
@elseif($po->status == 'closed') bg-success
@endif">
{{ strtoupper($po->status) }}
</span>
</td>

<td>
<div class="aksi-wrapper">

   <a href="{{ route('po-masuk.show',$po->id) }}"
   class="btn btn-info btn-sm btnDetail">
   Detail
</a>

    <button type="button"
            class="btn btn-danger btn-sm btnDelete"
            data-id="{{ $po->id }}"
            data-po="{{ $po->no_po_klien }}">
        Delete
    </button>

</div>
</td>

</tr>

@empty
<tr>
<td colspan="8" class="text-center text-muted py-4">
Belum ada data PO Masuk
</td>
</tr>
@endforelse

</tbody>
</table>
</div>

</div>
</div>

{{-- PAGINATION --}}
<div class="mt-3">
    {{ $poMasuk->withQueryString()->links() }}
</div>

</div>
{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus PO?</h5>

<p class="text-muted">
PO <strong id="deletePoName"></strong>
akan dihapus permanen.
</p>

<form id="deleteForm" method="POST">
@csrf
@method('DELETE')

<div class="d-flex justify-content-center gap-2">
<button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal" style="margin-right: 4px">
Batal
</button>

<button type="submit"
        class="btn btn-danger">
Hapus
</button>
</div>

</form>

</div>
</div>
</div>
</div>
{{-- ERROR MODAL --}}
<div class="modal fade" id="errorModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-x-circle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Terjadi Kesalahan</h5>
<div id="errorText" class="text-muted"></div>

<div class="mt-4">
<button class="btn btn-danger px-4"
        data-bs-dismiss="modal">
Tutup
</button>
</div>

</div>
</div>
</div>
</div>
{{-- LOADING MODAL --}}
<div class="modal fade"
     id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">
<div class="spinner-border text-primary mb-3"
     style="width:3rem;height:3rem;"></div>
<div class="fw-semibold">Memuat data...</div>
</div>
</div>
</div>
</div>
{{-- SUCCESS MODAL --}}
<div class="modal fade" id="successModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-check-circle-fill text-success"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Berhasil</h5>
<div id="successText" class="text-muted"></div>

<div class="mt-4">
<button class="btn btn-primary px-4"
        data-bs-dismiss="modal">
OK
</button>
</div>

</div>
</div>
</div>
</div>
<style>

.po-table th,
.po-table td{
    font-size:13px;
    padding:10px 12px;
    vertical-align:middle;
}

.table-hover tbody tr:hover{
    background-color:#f5f7fa;
}

.aksi-wrapper{
    display:flex;
    gap:6px;
    justify-content:center;
    align-items:center;
}

.aksi-wrapper form{
    margin:0;
}

.btn-sm{
    font-size:12px;
    padding:5px 12px;
}

</style>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // LOADING FILTER
    const filterForm = document.querySelector('form[action="{{ route('po-masuk.index') }}"]');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    if(filterForm){
        filterForm.addEventListener('submit', function(){
            loadingModal.show();
        });
    }

    // DELETE MODAL
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm  = document.getElementById('deleteForm');
    const deleteName  = document.getElementById('deletePoName');

    document.querySelectorAll('.btnDelete').forEach(btn=>{
        btn.addEventListener('click', function(){
            deleteName.textContent = this.dataset.po;
            deleteForm.action = `/po-masuk/${this.dataset.id}`;
            deleteModal.show();
        });
    });

    // ================= SUCCESS & ERROR =================
    const successInput = document.getElementById("success-message");
    const errorInput   = document.getElementById("error-message");

    if(successInput){
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );

        document.getElementById("successText").innerText =
            successInput.value;

        setTimeout(()=>{ successModal.show(); },200);
    }

    if(errorInput){
        const errorModal = new bootstrap.Modal(
            document.getElementById("errorModal")
        );

        document.getElementById("errorText").innerText =
            errorInput.value;

        setTimeout(()=>{ errorModal.show(); },200);
    }
    // DETAIL LOADING
document.querySelectorAll('.btnDetail').forEach(btn=>{
    btn.addEventListener('click', function(e){

        e.preventDefault();

        loadingModal.show();

        setTimeout(()=>{
            window.location.href = this.href;
        },200);

    });
});

});
</script>
@endsection
