@extends('layouts.app')

@section('content')

<style>
.working-report-table th,
.working-report-table td{
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
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-semibold">Working Report</h5>
</div>

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4">
<div class="card-body py-3 small">

<form method="GET" action="{{ route('working-report.index') }}"  id="filterForm">

<div class="row g-3 align-items-end">

    {{-- SEARCH --}}
    <div class="col-md-4">
        <label class="form-label small mb-1">Search</label>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control form-control-sm"
               placeholder="Project / Company / Vessel / No PO">
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

    {{-- BUTTON --}}
    <div class="col-md-4 d-flex gap-2">
        <button type="submit"
                class="btn btn-primary btn-sm px-3" style="margin-right: 4px">
            Filter
        </button>

        <a href="{{ route('working-report.index') }}"
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
<table class="table table-bordered table-hover align-middle working-report-table mb-0">

<thead class="table-light text-center">
<tr>
<th width="50">No</th>
<th width="200">Project</th>
<th width="200">Company</th>
<th width="160">Vessel</th>
<th width="150">PO Client</th>
<th width="130">Tanggal</th>
<th width="150">Action</th>
</tr>
</thead>

<tbody>

@forelse($workingReports as $index => $report)

<tr>

<td class="text-center">
    {{ $workingReports->firstItem() + $index }}
</td>

<td class="fw-semibold">
    {{ $report->project }}
</td>

<td>
    {{ $report->poMasuk->mitra_marine ?? '-' }}
</td>

<td>
    {{ $report->poMasuk->vessel ?? '-' }}
</td>

<td class="text-center">
    {{ $report->poMasuk->no_po_klien ?? '-' }}
</td>

<td class="text-center">
    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
</td>

<td>
<div class="aksi-wrapper">

    <a href="{{ route('working-report.show',$report->id) }}"
       class="btn btn-info btn-sm btnDetail">
        Detail
    </a>

</div>
</td>

</tr>

@empty
<tr>
<td colspan="7" class="text-center text-muted py-4">
Belum ada Working Report
</td>
</tr>
@endforelse

</tbody>
</table>
</div>

</div>

@if($workingReports->hasPages())
<div class="card-footer small">
    {{ $workingReports->withQueryString()->links() }}
</div>
@endif

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

});
</script>

@endsection
