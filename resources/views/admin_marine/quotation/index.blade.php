@extends('layouts.app')

@section('content')
<div class="container-fluid px-3">

    <h5 class="mb-3 fw-semibold">Rekap Quotation</h5>

    {{-- FILTER --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body py-3">

        <form method="GET" action="{{ route('quotations.index') }}">
            <div class="row align-items-end g-3">

                {{-- SEARCH --}}
                <div class="col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="Quote No / Mitra / Vessel / Project">
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
                            class="btn btn-primary btn-sm px-3" style="margin-right: 7px">
                        Filter
                    </button>

                    <a href="{{ route('quotations.index') }}"
                       class="btn btn-secondary btn-sm" style="margin-right: 7px">
                        Reset
                    </a>

                    <a href="{{ route('quotations.create') }}"
                       class="btn btn-success btn-sm ms-auto">
                        Buat Quotation
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>


    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle quotation-table">

            <thead class="table-light text-center">
                <tr>
                    <th width="40">No</th>
                    <th width="150">Quote No</th>
                    <th width="170">Mitra</th>
                    <th width="120">Vessel</th>
                    <th style="min-width:220px;">Project</th>
                    <th width="120">Date</th>
                    <th width="150">Grand Total</th>
                    <th width="190">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($quotations as $index => $q)
                <tr>

                    <td class="text-center">
                        {{ $quotations->firstItem() + $index }}
                    </td>

                    <td class="fw-semibold">
                        {{ $q->quote_no }}
                    </td>

                    <td>{{ $q->mitra_name }}</td>

                    <td>{{ $q->vessel_name }}</td>

                    <td class="project-col">
                        {{ $q->project ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ $q->date 
                            ? \Carbon\Carbon::parse($q->date)->format('d-m-Y')
                            : '-' }}
                    </td>

                    <td class="text-end fw-semibold">
                        Rp {{ number_format($q->grand_total,0,',','.') }}
                    </td>

                    <td>
                        <div class="aksi-wrapper">
                            <a href="{{ route('quotations.show',$q->id) }}"
                               class="btn btn-info btn-sm">
                                Detail
                            </a>

                            <a href="{{ route('quotations.edit',$q->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('quotations.destroy',$q->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus quotation ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Tidak ada data quotation
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $quotations->withQueryString()->links() }}
    </div>

</div>

<style>

.quotation-table {
    font-size: 13px;
}

.quotation-table th,
.quotation-table td {
    padding: 8px 10px;
    vertical-align: middle;
}

.project-col {
    white-space: normal;
    word-break: break-word;
}

.aksi-wrapper {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

.aksi-wrapper form {
    margin: 0;
}

.btn-sm {
    font-size: 12px;
    padding: 5px 10px;
}

.table-hover tbody tr:hover {
    background-color: #f5f7fa;
}
.filter-control {
    height: 38px;
    border-radius: 6px;
}

.filter-control:focus {
    box-shadow: 0 0 0 0.15rem rgba(13,110,253,.25);
}


</style>

@endsection
