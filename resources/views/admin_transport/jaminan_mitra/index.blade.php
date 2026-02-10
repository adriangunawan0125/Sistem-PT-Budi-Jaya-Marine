@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Data Jaminan Mitra</h4>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    {{-- TOMBOL TAMBAH --}}
    <a href="{{ route('jaminan_mitra.create') }}"
       class="btn btn-primary mb-3">
        Tambah Jaminan
    </a>

    {{-- SEARCH --}}
    <form method="GET" class="mb-3" id="filterForm">
        <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari nama mitra / no hp / jaminan..."
                value="{{ request('search') }}"
                style="width: 300px;"
            >

            <button type="submit" class="btn btn-primary px-3">
                Cari
            </button>

            <a href="{{ route('jaminan_mitra.index') }}"
               class="btn btn-secondary px-3">
                Reset
            </a>

        </div>
    </form>

    {{-- TABLE --}}
    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Nama Mitra</th>
                <th>No HP</th>
                <th>Jaminan</th>
                <th width="220">Gambar</th>
                <th width="200" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($data as $no => $item)
            <tr>
                <td>{{ $data->firstItem() + $no }}</td>

                <td class="fw-semibold">
                    {{ $item->mitra->nama_mitra }}
                </td>

                <td>{{ $item->mitra->no_hp }}</td>

                <td>{{ $item->jaminan }}</td>

                {{-- GAMBAR --}}
                <td>
                    @foreach(['gambar_1','gambar_2','gambar_3'] as $g)
                        @if($item->$g)
                            <a href="{{ asset('storage/'.$item->$g) }}" target="_blank">
                                <img src="{{ asset('storage/'.$item->$g) }}"
                                     width="50"
                                     class="me-1 mb-1 rounded border">
                            </a>
                        @endif
                    @endforeach
                </td>

                {{-- AKSI --}}
                <td class="text-center">

                    <a href="{{ route('jaminan_mitra.edit', $item->id) }}"
                       class="btn btn-warning btn-sm mb-1">
                        Edit
                    </a>

                    <button type="button"
                            class="btn btn-danger btn-sm mb-1"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->mitra->nama_mitra }} - {{ $item->jaminan }}">
                        Hapus
                    </button>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Data jaminan tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $data->links() }}
    </div>

</div>

<!-- ================= SUCCESS MODAL ================= -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Berhasil</h5>
                <div id="successText" class="text-muted"></div>

                <div class="mt-4">
                    <button class="btn btn-primary px-4" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================= DELETE MODAL ================= -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Hapus Data Jaminan?</h5>

                <p class="text-muted mb-4">
                    Data <strong id="deleteName"></strong> akan dihapus permanen
                    dan tidak bisa dikembalikan.
                </p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" style="margin-right: 10px"
                                class="btn btn-secondary px-4"
                                data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit"
                                class="btn btn-danger px-4">
                            Ya, Hapus
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<!-- ================= LOADING MODAL ================= -->
<div class="modal fade" id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
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

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

    // LOADING SAAT SEARCH
    const filterForm = document.getElementById("filterForm");
    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    if(filterForm){
        filterForm.addEventListener("submit", function(e){
            e.preventDefault();
            loadingModal.show();

            setTimeout(() => {
                filterForm.submit();
            }, 150);
        });
    }

    // DELETE MODAL
    const deleteModal = document.getElementById("deleteModal");
    const deleteForm  = document.getElementById("deleteForm");
    const deleteName  = document.getElementById("deleteName");

    deleteModal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute("data-id");
        const name   = button.getAttribute("data-name");

        deleteName.textContent = name;
        deleteForm.action = `/jaminan_mitra/${id}`;
    });

    // SUCCESS MODAL
    const successInput = document.getElementById("success-message");
    if(successInput){
        const successModal = new bootstrap.Modal(
            document.getElementById("successModal")
        );
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(() => {
            successModal.show();
        }, 250);
    }

});
</script>

@endsection
