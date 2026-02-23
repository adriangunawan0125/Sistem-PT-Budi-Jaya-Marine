@extends('layouts.app')

@section('content')

<style>
    .compact-card .card-body {
        padding: 1rem 1.25rem;
    }

    .compact-table th,
    .compact-table td {
        font-size: 12.5px;
        padding: 8px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .compact-table thead th {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .filter-label {
        font-size: 11.5px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .btn-xs {
        padding: 3px 8px;
        font-size: 11.5px;
    }
</style>

<div class="container">

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-4 compact-card">
        <div class="card-body">

            <form method="GET" action="{{ route('pemasukan-marine.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="filter-label">Search</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control form-control-sm"
                               placeholder="No PO / Company / Vessel / Tanggal">
                    </div>

                    <div class="col-md-2">
                        <label class="filter-label">Bulan</label>
                        <select name="month"
                                class="form-control form-control-sm">
                            <option value="">Semua</option>
                            @for($m=1;$m<=12;$m++)
                                <option value="{{ $m }}"
                                    {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="filter-label">Tahun</label>
                        <select name="year"
                                class="form-control form-control-sm">
                            <option value="">Semua</option>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}"
                                    {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary btn-xs" style="margin-right: 4px">
                            Filter
                        </button>

                        <a href="{{ route('pemasukan-marine.index') }}"
                           class="btn btn-secondary btn-xs" style="margin-right: 15px">
                            Reset
                        </a>

                        <a href="{{ route('pemasukan-marine.create') }}"
                           class="btn btn-success btn-xs ms-auto">
                            + Tambah
                        </a>

                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0 compact-card">
        <div class="card-body table-responsive">

            <table class="table compact-table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">No</th>
                        <th>No PO</th>
                        <th>Company</th>
                        <th>Vessel</th>
                        <th>Tanggal</th>
                        <th>Pengirim</th>
                        <th>Nominal</th>
                        <th width="80">Bukti</th>
                        <th width="140" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemasukan as $item)
                    <tr>
                        <td>
                            {{ $loop->iteration + ($pemasukan->currentPage()-1)*$pemasukan->perPage() }}
                        </td>
                        <td>{{ $item->poMasuk->no_po_klien ?? '-' }}</td>
                        <td>{{ $item->poMasuk->mitra_marine ?? '-' }}</td>
                        <td>{{ $item->poMasuk->vessel ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>{{ $item->nama_pengirim }}</td>
                        <td>{{ number_format($item->nominal, 2, ',', '.') }}</td>
                        <td>
                            @if($item->bukti)
                                <a href="{{ asset('storage/'.$item->bukti) }}"
                                   target="_blank"
                                   class="btn btn-primary btn-xs">
                                   Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">

                                <a href="{{ route('pemasukan-marine.show',$item->id) }}"
                                   class="btn btn-info btn-xs btnDetail" style="margin-right: 4px">
                                   Detail
                                </a>

                                <a href="{{ route('pemasukan-marine.edit',$item->id) }}"
                                   class="btn btn-warning btn-xs" style="margin-right: 4px">
                                   Edit
                                </a>

                              <form action="{{ route('pemasukan-marine.destroy',$item->id) }}"
      method="POST"
      class="deleteForm">
    @csrf
    @method('DELETE')
    <button type="button"
            class="btn btn-danger btn-xs btnDelete">
        Hapus
    </button>
</form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $pemasukan->withQueryString()->links() }}
            </div>

        </div>
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
Yakin ingin menghapus data ini?
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

{{-- SUCCESS MODAL --}}
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
        class="btn btn-primary px-4"
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

    const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
    );

    let deleteForm;

    // ================= DETAIL =================
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

    // ================= FILTER =================
    const filterForm = document.getElementById("filterForm");
    if(filterForm){
        filterForm.addEventListener("submit", function(){
            loadingModal.show();
        });
    }

    // ================= DELETE =================
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

    // ================= SUCCESS =================
    @if(session('success'))
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        successModal.show();
    @endif

});
</script>
@endsection