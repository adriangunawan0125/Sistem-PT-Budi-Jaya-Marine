@extends('layouts.app')

@section('content')
<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Delivery Order (All PO)</h4>
</div>

{{-- ================= FILTER ================= --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body py-3">

        <form method="GET" action="{{ route('delivery-order.index') }}" id="filterForm">
            <div class="row align-items-end g-3">

                {{-- SEARCH --}}
                <div class="col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="No DO / No PO / Vessel / Company">
                </div>

                {{-- BULAN --}}
                <div class="col-md-2">
                    <select name="month"
                            class="form-control form-control-sm">
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
                            class="form-control form-control-sm">
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
                            class="btn btn-primary btn-sm px-3" style="margin-right: 4px">
                        Filter
                    </button>

                    <a href="{{ route('delivery-order.index') }}"
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
<table class="table table-bordered table-hover align-middle do-table mb-0">

<thead class="table-light text-center">
<tr>
<th width="50">No</th>
<th width="150">No DO</th>
<th width="170">No PO</th>
<th width="200">Company</th>
<th width="160">Vessel</th>
<th width="130">Tanggal DO</th>
<th width="140">Status</th>
<th width="200">Action</th>
</tr>
</thead>

<tbody>

@forelse($deliveryOrders as $index => $do)

<tr>

<td class="text-center">
    {{ $deliveryOrders->firstItem() + $index }}
</td>

<td class="fw-semibold text-center">
    {{ $do->no_do }}
</td>

<td class="text-center">
    {{ $do->poMasuk->no_po_klien ?? '-' }}
</td>

<td>
    {{ $do->poMasuk->mitra_marine ?? '-' }}
</td>

<td>
    {{ $do->poMasuk->vessel ?? '-' }}
</td>

<td class="text-center">
    {{ \Carbon\Carbon::parse($do->tanggal_do)->format('d-m-Y') }}
</td>

<td class="text-center">
    <span class="badge text-light
        @if($do->status == 'draft') bg-secondary
        @elseif($do->status == 'approved') bg-primary
        @elseif($do->status == 'delivered') bg-success
        @elseif($do->status == 'cancelled') bg-danger
        @endif">
        {{ strtoupper($do->status) }}
    </span>
</td>

<td>
<div class="aksi-wrapper">

    <a href="{{ route('delivery-order.show',$do->id) }}"
       class="btn btn-info btn-sm btnDetail">
       Detail
    </a>

    

</div>
</td>

</tr>

@empty
<tr>
<td colspan="8" class="text-center text-muted py-4">
Belum ada Delivery Order
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
    {{ $deliveryOrders->withQueryString()->links() }}
</div>

</div>

<style>

.do-table th,
.do-table td{
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

@if(session('success'))
<div class="modal fade"
     id="successModal"
     tabindex="-1">

<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">

<div class="modal-body text-center py-4">

<i class="bi bi-check-circle-fill text-success"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Berhasil</h5>

<div class="text-muted mb-4">
    {{ session('success') }}
</div>

<button type="button"
        class="btn btn-success px-4"
        data-bs-dismiss="modal">
    OK
</button>

</div>
</div>
</div>
</div>
@endif


<script>
document.addEventListener("DOMContentLoaded", function(){

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    // ================= DETAIL =================
    document.querySelectorAll(".btnDetail").forEach(btn => {
        btn.addEventListener("click", function(e){
            e.preventDefault();
            loadingModal.show();
            setTimeout(() => {
                window.location.href = this.href;
            }, 250);
        });
    });

    // ================= FILTER =================
    const filterForm = document.getElementById("filterForm");

    if(filterForm){
        filterForm.addEventListener("submit", function(){
            loadingModal.show();
        });
    }

    // ================= PAGINATION =================
    document.querySelectorAll(".pagination a").forEach(link => {
        link.addEventListener("click", function(e){
            e.preventDefault();
            loadingModal.show();
            setTimeout(() => {
                window.location.href = this.href;
            }, 250);
        });
    });

    // ================= SUCCESS MODAL =================
    @if(session('success'))
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        successModal.show();
    @endif

});
</script>
@endsection
