@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Data Mitra Marine</h4>

    <a href="{{ route('mitra-marine.create') }}"
       class="btn btn-primary mb-3 trigger-loading">
        + Mitra Baru
    </a>

    {{-- SEARCH --}}
    <form method="GET"
          class="row g-2 mb-3"
          id="searchForm">

        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama perusahaan..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Cari
            </button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('mitra-marine.index') }}"
               class="btn btn-secondary w-100 trigger-loading">
                Reset
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th style="width:150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                <tr>
                    <td>{{ $data->firstItem() + $index }}</td>

                    <td>{{ $row->nama_mitra }}</td>
                    <td>{{ $row->address ?? '-' }}</td>
                    <td>{{ $row->telp ?? '-' }}</td>

                   <td class="text-center">
    <div class="d-flex justify-content-center align-items-center">

        <a href="{{ route('mitra-marine.show', $row->id) }}"
           class="btn btn-info btn-sm me-3 trigger-loading" style="margin-right: 4px">
            Detail
        </a>

        <a href="{{ route('mitra-marine.edit', $row->id) }}"
           class="btn btn-warning btn-sm me-3 trigger-loading" style="margin-right: 4px">
            Edit
        </a>

        <form action="{{ route('mitra-marine.delete', $row->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Hapus mitra ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                Hapus
            </button>
        </form>

    </div>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $data->links('pagination::bootstrap-5') }}
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
                <div class="fw-semibold">Memuat data...</div>
            </div>
        </div>
    </div>
</div>


{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const loadingModal = new bootstrap.Modal(
        document.getElementById("loadingModal")
    );

    const searchForm = document.getElementById("searchForm");
    if (searchForm) {
        searchForm.addEventListener("submit", function () {
            loadingModal.show();
        });
    }

    document.querySelectorAll(".trigger-loading").forEach(el => {
        el.addEventListener("click", function () {
            loadingModal.show();
        });
    });

    document.querySelectorAll(".pagination a").forEach(link => {
        link.addEventListener("click", function () {
            loadingModal.show();
        });
    });

});
</script>
@endsection
