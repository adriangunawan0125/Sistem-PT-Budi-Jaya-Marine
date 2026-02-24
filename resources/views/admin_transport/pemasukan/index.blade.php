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

            <form method="GET"
                  action="{{ route('pemasukan.index') }}"
                  id="filterForm">

                <div class="row g-3 align-items-end">

                    <div class="col-md-2">
                        <label class="filter-label">Tanggal</label>
                        <input type="date"
                               name="tanggal"
                               value="{{ request('tanggal', date('Y-m-d')) }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="filter-label">Mitra</label>
                        <input type="text"
                               name="nama"
                               value="{{ request('nama') }}"
                               class="form-control form-control-sm"
                               placeholder="Cari nama mitra">
                    </div>

                    <div class="col-md-2">
                        <label class="filter-label">Kategori</label>
                        <select name="kategori"
                                class="form-control form-control-sm">
                            <option value="">Semua</option>
                            <option value="setoran" {{ request('kategori')=='setoran'?'selected':'' }}>Setoran</option>
                            <option value="cicilan" {{ request('kategori')=='cicilan'?'selected':'' }}>Cicilan</option>
                            <option value="deposit" {{ request('kategori')=='deposit'?'selected':'' }}>Deposit</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="tidak_setor"
                                   value="1"
                                   {{ request('tidak_setor')?'checked':'' }}>
                            <label class="form-check-label small">
                                Tidak TF
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary btn-xs" style="margin-right: 4px">
                            Filter
                        </button>

                        <a href="{{ route('pemasukan.index') }}"
                           class="btn btn-secondary btn-xs" style="margin-right: 4px">
                            Reset
                        </a>

                        <a href="{{ route('pemasukan.create') }}"
                           class="btn btn-success btn-xs ms-auto" style="margin-right: 4px">
                            + Tambah
                        </a>

                    </div>

                </div>
            </form>

        </div>
    </div>
{{-- CARD MITRA TIDAK SETOR --}}
@if(isset($mitraKosong))
<div class="card border-danger mb-3">
    <div class="card-header bg-danger text-white">
        Mitra Tidak TF ({{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }})
    </div>
    <div class="card-body">
        @forelse($mitraKosong as $m)
            <span class="badge text-light bg-secondary me-1 mb-1">
                {{ $m->nama_mitra }}
            </span>
        @empty
            <span class="text-muted">
                Semua mitra sudah transfer
            </span>
        @endforelse
    </div>
</div>
@endif

    {{-- TABLE --}}
    <div class="card shadow-sm border-0 compact-card">
        <div class="card-body table-responsive">

            <table class="table compact-table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th width="40">No</th>
                    <th>Tanggal</th>
                    <th>Mitra</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th width="80">Bukti</th>
                    <th>Nominal</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
                </thead>

                <tbody>
                @forelse($pemasukan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td>{{ $item->mitra->nama_mitra ?? '-' }}</td>
                    <td>{{ ucfirst($item->kategori) }}</td>
                    <td>{{ $item->deskripsi }}</td>

                  <td>
    @if($item->gambar || $item->gambar1)
        <button type="button"
                class="btn btn-primary btn-xs btnPreview"
                data-gambar="{{ $item->gambar ? asset('storage/pemasukan/'.$item->gambar) : '' }}"
                data-gambar1="{{ $item->gambar1 ? asset('storage/pemasukan/'.$item->gambar1) : '' }}">
            Lihat
        </button>
    @else
        -
    @endif
</td>

                    <td>
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">

                            <a href="{{ route('pemasukan.show',$item->id) }}"
                               class="btn btn-info btn-xs btnDetail" style="margin-right: 4px">
                               Detail
                            </a>

                            <a href="{{ route('pemasukan.edit',$item->id) }}"
                               class="btn btn-warning btn-xs" style="margin-right: 4px">
                               Edit
                            </a>

                            <form action="{{ route('pemasukan.destroy',$item->id) }}"
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
                    <td colspan="8"
                        class="text-center py-4 text-muted">
                        Tidak ada data ditemukan
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>

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
                            data-bs-dismiss="modal">
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

{{-- ================= PREVIEW IMAGE MODAL ================= --}}
<div class="modal fade"
     id="previewModal"
     tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    Bukti Transfer
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div id="previewContainer"
                     class="row g-3 justify-content-center">
                </div>

            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    /* ================= PREVIEW MODAL ================= */

    const previewModal = new bootstrap.Modal(
        document.getElementById("previewModal")
    );

    document.querySelectorAll(".btnPreview").forEach(btn => {

        btn.addEventListener("click", function(){

            const gambar  = this.dataset.gambar;
            const gambar1 = this.dataset.gambar1;

            const container = document.getElementById("previewContainer");
            container.innerHTML = "";

            function createImage(src, label){
                if(!src) return;

                container.innerHTML += `
                    <div class="col-md-6 text-center">
                        <p class="small text-muted mb-2">${label}</p>
                        <img src="${src}"
                             class="img-fluid rounded shadow"
                             style="max-height:400px; cursor:pointer;"
                             onclick="window.open('${src}','_blank')">
                    </div>
                `;
            }

            createImage(gambar, "Bukti TF 1");
            createImage(gambar1, "Bukti TF 2");

            previewModal.show();
        });

    });

    // CLEAR MODAL SAAT DITUTUP
    document.getElementById("previewModal")
    .addEventListener("hidden.bs.modal", function(){
        document.getElementById("previewContainer").innerHTML = "";
    });


    /* ================= LOADING MODAL ================= */

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

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

    const filterForm = document.getElementById("filterForm");
    if(filterForm){
        filterForm.addEventListener("submit", function(){
            loadingModal.show();
        });
    }


    /* ================= DELETE MODAL ================= */

    const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
    );

    let deleteForm;

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


    /* ================= SUCCESS MODAL ================= */

    @if(session('success'))
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        successModal.show();
    @endif

});
</script>

@endsection