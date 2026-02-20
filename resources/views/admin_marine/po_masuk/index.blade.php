@extends('layouts.app')

@section('content')
<div class="container">

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
       class="btn btn-info btn-sm">
       Detail
    </a>

    <form action="{{ route('po-masuk.destroy',$po->id) }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin hapus PO ini?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-danger btn-sm">
            Delete
        </button>
    </form>
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

@endsection
