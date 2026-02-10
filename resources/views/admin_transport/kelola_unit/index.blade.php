@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Unit</h4>

    {{-- ALERT --}}
   @if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

    {{-- TOMBOL TAMBAH --}}
    <a href="{{ url('/admin-transport/unit/create') }}"
       class="btn btn-primary mb-3">
        Tambah Unit
    </a>

    {{-- SEARCH & FILTER --}}
    <form method="GET" class="mb-3" id="filterForm">
        <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari nama unit..."
                value="{{ request('search') }}"
                style="width: 240px;"
            >

            {{-- STATUS --}}
            <select
                name="status"
                class="form-control"
                style="width: 180px;"
            >
                <option value="">-- Semua Status --</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>
                    Tersedia
                </option>
                <option value="disewakan" {{ request('status') == 'disewakan' ? 'selected' : '' }}>
                    Disewakan
                </option>
            </select>

            <button type="submit" class="btn btn-primary px-3">
                Filter
            </button>

            <a href="{{ url('/admin-transport/unit') }}"
               class="btn btn-secondary px-3">
                Reset
            </a>

        </div>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Nama Unit</th>
                <th>Merek Mobil</th>
                <th>Status</th>
                <th>Masa Berlaku STNK</th>
                <th width="220">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($units as $no => $unit)
            <tr>
                <td>{{ $units->firstItem() + $no }}</td>

                <td class="fw-semibold">
                    {{ $unit->nama_unit }}
                </td>

                <td>{{ $unit->merek }}</td>

                {{-- STATUS --}}
                <td>
                    @if($unit->status === 'tersedia')
                        <span class="badge bg-success text-white px-3 py-2 d-inline-block text-center"
                              style="min-width:110px">
                            Tersedia
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 d-inline-block text-center"
                              style="min-width:110px">
                            Disewakan
                        </span>
                    @endif
                </td>
{{-- MASA BERLAKU STNK --}}
<td>
    @if($unit->stnk_expired_at)
        @php
            $stnk = \Carbon\Carbon::parse($unit->stnk_expired_at)->format('Y-m-d');
            $sisa_hari = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($unit->stnk_expired_at), false);
        @endphp

        @if($sisa_hari < 0)
            <span class="badge bg-dark text-white px-3 py-2 d-inline-block text-center" style="min-width:110px">
                Expired
            </span>
        @elseif($sisa_hari <= 7)
            <span class="badge bg-danger text-white px-3 py-2 d-inline-block text-center" style="min-width:110px">
                {{ $stnk }} ({{ $sisa_hari }} hari lagi)
            </span>
        @else
            <span class="badge bg-success text-white px-3 py-2 d-inline-block text-center" style="min-width:110px">
                {{ $stnk }}
            </span>
        @endif
    @else
        <span class="text-muted">Belum diisi</span>
    @endif
</td>


                {{-- AKSI --}}
                <td>
                    <a href="{{ url('/admin-transport/unit/edit/'.$unit->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ url('/admin-transport/unit/delete/'.$unit->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin hapus unit ini? Data tidak bisa dikembalikan!')">
                        @csrf
                        @method('DELETE')
              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $unit->id }}" data-name="{{ $unit->nama_unit }}">
    Hapus
</button>

                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Data unit tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center">
        {{ $units->links() }}
    </div>

</div>

<!-- SUCCESS MODAL -->
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
<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Hapus Data Unit?</h5>

                <p class="text-muted mb-4">
                    Unit <strong id="deleteUnitName"></strong> akan dihapus permanen
                    dan tidak bisa dikembalikan.
                </p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-secondary px-4" style="margin-right: 10px"
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

<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memuat data...</div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const filterForm = document.getElementById("filterForm");
    if(!filterForm) return;

    const modal = new bootstrap.Modal(document.getElementById("loadingModal"));

    filterForm.addEventListener("submit", function(e){
        e.preventDefault();

        modal.show();

        setTimeout(() => {
            filterForm.submit();
        }, 150);
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const deleteModal = document.getElementById('deleteModal');
    const deleteForm  = document.getElementById('deleteForm');
    const unitName    = document.getElementById('deleteUnitName');

    deleteModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const name   = button.getAttribute('data-name');

        unitName.textContent = name;
        deleteForm.action = `/admin-transport/unit/delete/${id}`;
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const successInput = document.getElementById("success-message");

    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        // kasih delay biar page render dulu
        setTimeout(() => {
            modal.show();
        }, 250);
    }
});
</script>

@endsection
