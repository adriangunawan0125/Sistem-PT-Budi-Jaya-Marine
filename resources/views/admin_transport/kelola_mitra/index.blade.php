@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Mitra</h4>

    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- TOMBOL TAMBAH --}}
    <a href="{{ url('/admin-transport/mitra/create') }}"
       class="btn btn-primary mb-3">
        Tambah Mitra
    </a>

    {{-- SEARCH --}}
    <form method="GET" class="row mb-3" id="filterForm">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama mitra..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-5">
            <button class="btn btn-primary">Cari</button>
            <a href="{{ url('/admin-transport/mitra') }}" class="btn btn-secondary">Reset</a>
            <a href="{{ url('/admin-transport/mitra/berakhir') }}"
               class="btn btn-outline-dark">
                Ex-Mitra
            </a>
        </div>
    </form>

    {{-- TABEL --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Nama Mitra</th>
                <th style="min-width:160px">Unit</th>
                <th>No HP</th>
                <th width="200">Kontrak</th>
                <th width="140" class="text-center">Status</th>
                <th width="320" class="text-center">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($mitras as $no => $mitra)
                @php
                    $hariIni = now()->startOfDay();
                    $aktif = is_null($mitra->kontrak_berakhir)
                        || $hariIni->lte(\Carbon\Carbon::parse($mitra->kontrak_berakhir));
                @endphp

                <tr>
                    <td>{{ $mitras->firstItem() + $no }}</td>

                    <td class="fw-semibold">{{ $mitra->nama_mitra }}</td>

                    <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                        title="{{ $mitra->unit->nama_unit ?? '-' }}">
                        {{ $mitra->unit->nama_unit ?? '-' }}
                    </td>

                    <td>{{ $mitra->no_hp }}</td>

                    <td>
                        <small class="text-muted">
                            Mulai : {{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}<br>
                            Selesai : {{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}
                        </small>
                    </td>

                    <td class="text-center">
                        <span class="badge text-white {{ $aktif ? 'bg-success' : 'bg-danger' }}"
                              style="min-width:110px;padding:8px 0;">
                            {{ $aktif ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>

                    {{-- AKSI --}}
                    <td class="text-center">

                        {{-- DETAIL --}}
                        <a href="{{ url('/admin-transport/mitra/'.$mitra->id) }}"
                           class="btn btn-info btn-sm me-1 mb-1 btn-detail">
                            Detail
                        </a>

                        <a href="{{ url('/admin-transport/mitra/'.$mitra->id.'/edit') }}"
                           class="btn btn-warning btn-sm me-1 mb-1">
                            Edit
                        </a>

                        <button type="button"
                                class="btn btn-danger btn-sm me-1 mb-1"
                                data-bs-toggle="modal"
                                data-bs-target="#actionModal"
                                data-action="akhiri"
                                data-id="{{ $mitra->id }}"
                                data-name="{{ $mitra->nama_mitra }}">
                            Akhiri
                        </button>

                        <button type="button"
                                class="btn btn-danger btn-sm mb-1"
                                data-bs-toggle="modal"
                                data-bs-target="#actionModal"
                                data-action="hapus"
                                data-id="{{ $mitra->id }}"
                                data-name="{{ $mitra->nama_mitra }}">
                            Hapus
                        </button>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Data mitra tidak ditemukan
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $mitras->links() }}
    </div>

</div>


{{-- ================= SUCCESS MODAL ================= --}}
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                <h5 class="fw-bold mt-3">Berhasil</h5>
                <div id="successText" class="text-muted"></div>
                <button class="btn btn-primary mt-4 px-4" data-bs-dismiss="modal">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ================= ACTION MODAL ================= --}}
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">

                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:60px;"></i>

                <h5 class="fw-bold mt-3" id="actionTitle"></h5>

                <p class="text-muted">
                    Mitra <strong id="actionName"></strong>
                </p>

                <form id="actionForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="actionMethod">

                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-secondary px-4"
                                data-bs-dismiss="modal" style="margin-right:7px">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger px-4">
                            Ya, Lanjutkan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


{{-- ================= LOADING SEARCH ================= --}}
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memuat data...</div>
            </div>
        </div>
    </div>
</div>

{{-- ================= LOADING DETAIL ================= --}}
<div class="modal fade" id="detailLoadingModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-info mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memuat detail mitra...</div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    /* SUCCESS */
    const successInput = document.getElementById("success-message");
    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;
        setTimeout(() => modal.show(), 200);
    }

    /* SEARCH LOADING */
    const filterForm = document.getElementById("filterForm");
    if(filterForm){
        const loadingModal = new bootstrap.Modal(document.getElementById("loadingModal"));
        filterForm.addEventListener("submit", function(e){
            e.preventDefault();
            loadingModal.show();
            setTimeout(() => filterForm.submit(), 150);
        });
    }

    /* DETAIL LOADING */
    const detailModal = new bootstrap.Modal(document.getElementById("detailLoadingModal"));
    document.querySelectorAll(".btn-detail").forEach(btn => {
        btn.addEventListener("click", function(e){
            e.preventDefault();
            const url = this.getAttribute("href");
            detailModal.show();
            setTimeout(() => {
                window.location.href = url;
            }, 150);
        });
    });

    /* ACTION MODAL */
    const actionModal = document.getElementById("actionModal");
    const actionForm  = document.getElementById("actionForm");

    actionModal.addEventListener("show.bs.modal", function (event) {

        const btn  = event.relatedTarget;
        const id   = btn.getAttribute("data-id");
        const name = btn.getAttribute("data-name");
        const type = btn.getAttribute("data-action");

        document.getElementById("actionName").innerText = name;

        if(type === "akhiri"){
            document.getElementById("actionTitle").innerText = "Akhiri Kontrak Mitra?";
            actionForm.action = `/admin-transport/mitra/${id}/akhiri-kontrak`;
            document.getElementById("actionMethod").value = "PATCH";
        } else {
            document.getElementById("actionTitle").innerText = "Hapus Mitra?";
            actionForm.action = `/admin-transport/mitra/${id}`;
            document.getElementById("actionMethod").value = "DELETE";
        }
    });

});
</script>

@endsection
