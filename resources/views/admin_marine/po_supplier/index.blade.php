@extends('layouts.app')

@section('content')

<style>
.filter-control{
    font-size:12px;
    height:32px;
    border-radius:6px;
}
.table th{
    font-size:12px;
}
.table td{
    font-size:12px;
    vertical-align:middle;
}
</style>
{{-- SUCCESS --}}
@if(session('success'))
<input type="hidden" id="success-message"
       value="{{ session('success') }}">
@endif

{{-- ERROR --}}
@if(session('error'))
<input type="hidden" id="error-message"
       value="{{ session('error') }}">
@endif
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold">PO Supplier</h5>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body small">

            <form method="GET">
                <div class="row g-2 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-md-4">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control form-control-sm filter-control"
                               placeholder="Search No PO / Supplier / PO Client">
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

                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100">
                            Filter
                        </button>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('po-supplier.index') }}"
                           class="btn btn-secondary btn-sm w-100">
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

            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>No PO</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>PO Client</th>
                        <th class="text-end">Grand Total</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($poSuppliers as $po)

                    @php
                        $badge = match($po->status){
                            'approved' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration + ($poSuppliers->currentPage() - 1) * $poSuppliers->perPage() }}</td>

                        <td>
                            <strong>{{ $po->no_po_internal }}</strong>
                        </td>

                        <td>{{ $po->nama_perusahaan }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($po->tanggal_po)->format('d M Y') }}
                        </td>

                        <td>
                            {{ $po->poMasuk->no_po_klien ?? '-' }}
                        </td>

                        <td class="text-end fw-semibold">
                            Rp {{ number_format($po->grand_total,0,',','.') }}
                        </td>

                        <td>
                            <span class="badge text-light px-3 py-2 {{ $badge }}">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
<td class="align-middle">
    <div class="d-flex align-items-center">

        <a href="{{ route('po-supplier.show',$po->id) }}"
           class="btn btn-info btn-sm mr-2 btnDetail">
            Detail
        </a>

        <a href="{{ route('po-supplier.edit',$po->id) }}"
           class="btn btn-warning btn-sm mr-2">
            Edit
        </a>

        <form action="{{ route('po-supplier.destroy',$po->id) }}"
              method="POST"
              class="mb-0"
              style="display:inline-block;"
            class="deleteForm"
data-id="{{ $po->id }}"
data-no="{{ $po->no_po_internal }}">
            @csrf
            @method('DELETE')
            <button type="button"
        class="btn btn-danger btn-sm btnDelete"
        data-id="{{ $po->id }}"
        data-no="{{ $po->no_po_internal }}">
    Delete
</button>
        </form>

    </div>
</td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center p-4 text-muted">
                            Belum ada PO Supplier
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

    <div class="mt-3">
        {{ $poSuppliers->links() }}
    </div>

</div>
{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-body text-center py-4">

<i class="bi bi-exclamation-triangle-fill text-danger"
   style="font-size:60px;"></i>

<h5 class="fw-bold mt-3">Hapus PO Supplier?</h5>

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

<div class="fw-semibold" id="loadingText">
Memuat data...
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

    const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
    );

    const loadingText = document.getElementById("loadingText");
    const deleteForm  = document.getElementById("deleteForm");
    const deleteName  = document.getElementById("deletePoName");

    // ================= FILTER LOADING =================
    const filterForm = document.querySelector('form[method="GET"]');
    if(filterForm){
        filterForm.addEventListener('submit', function(){
            loadingText.innerText = "Memuat data...";
            loadingModal.show();
        });
    }

    // ================= DETAIL / EDIT =================
    document.querySelectorAll('.btnDetail').forEach(btn=>{
        btn.addEventListener('click', function(e){
            e.preventDefault();
            loadingText.innerText = "Memuat halaman...";
            loadingModal.show();

            setTimeout(()=>{
                window.location.href = this.href;
            },200);
        });
    });

    // ================= DELETE =================
    document.querySelectorAll('.btnDelete').forEach(btn=>{
        btn.addEventListener('click', function(){

            deleteName.textContent = this.dataset.no;
            deleteForm.action = `/po-supplier/${this.dataset.id}`;

            deleteModal.show();
        });
    });

});

</script>
@endsection
