@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Mitra Kontrak Berakhir</h4>

    {{-- SUCCESS TRIGGER --}}
    @if(session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    {{-- SEARCH --}}
    <form method="GET" class="row mb-3" id="searchForm">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama mitra..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary">Cari</button>
            <a href="{{ url('/admin-transport/mitra/berakhir') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th width="60">No</th>
            <th>Nama Mitra</th>
            <th>No HP</th>
            <th>Kontrak</th>
            <th>Status</th>
            <th width="180">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse($mitras as $no => $mitra)
            <tr>
                <td>{{ $mitras->firstItem() + $no }}</td>
                <td class="fw-semibold">{{ $mitra->nama_mitra }}</td>
                <td>{{ $mitra->no_hp }}</td>
                <td>
                    <small class="text-muted">Mulai</small> :
                    {{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}<br>
                    <small class="text-muted">Selesai</small> :
                    {{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}
                </td>
                <td>
                    <span class="badge bg-danger px-3 py-2 text-light" style="min-width:110px">
                        Berakhir
                    </span>
                </td>
                <td>
                    {{-- AKTIFKAN --}}
                    <button class="btn btn-success btn-sm"
                            onclick="openAktifkanModal('{{ url('/admin-transport/mitra/'.$mitra->id.'/aktifkan') }}')">
                        Aktifkan
                    </button>

                    {{-- HAPUS --}}
                    <button class="btn btn-danger btn-sm"
                            onclick="openHapusModal('{{ url('/admin-transport/mitra/'.$mitra->id) }}')">
                        Hapus
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    Data mitra berakhir tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <a href="{{ url('/admin-transport/mitra') }}" class="btn btn-secondary mb-3">
        Kembali
    </a>

    <div class="d-flex justify-content-center">
        {{ $mitras->links() }}
    </div>
</div>


{{-- ================= MODAL KONFIRMASI AKTIFKAN ================= --}}
<div class="modal fade" id="aktifkanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle text-success" style="font-size:60px;"></i>
                <h5 class="fw-bold mt-3">Aktifkan Mitra?</h5>
                <p class="text-muted">Mitra akan diaktifkan kembali.</p>

                <form method="POST" id="aktifkanForm">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-success px-4">Ya, Aktifkan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ================= MODAL KONFIRMASI HAPUS ================= --}}
<div class="modal fade" id="hapusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <i class="bi bi-trash-fill text-danger" style="font-size:60px;"></i>
                <h5 class="fw-bold mt-3">Hapus Mitra?</h5>
                <p class="text-muted">
                    Data akan dihapus permanen dan tidak bisa dikembalikan.
                </p>

                <form method="POST" id="hapusForm">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger px-4">Ya, Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ================= LOADING MODAL (AKSI) ================= --}}
<div class="modal fade" id="loadingActionModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memproses data...</div>
            </div>
        </div>
    </div>
</div>


{{-- ================= LOADING MODAL (SEARCH) ================= --}}
<div class="modal fade" id="loadingSearchModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Mencari data...</div>
            </div>
        </div>
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
                <button class="btn btn-success mt-3 px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<script>
let aktifkanModal, hapusModal, loadingActionModal, loadingSearchModal;

document.addEventListener("DOMContentLoaded", function () {

    aktifkanModal = new bootstrap.Modal(document.getElementById("aktifkanModal"));
    hapusModal = new bootstrap.Modal(document.getElementById("hapusModal"));
    loadingActionModal = new bootstrap.Modal(document.getElementById("loadingActionModal"));
    loadingSearchModal = new bootstrap.Modal(document.getElementById("loadingSearchModal"));

    /* SEARCH LOADING */
    document.getElementById("searchForm").addEventListener("submit", function () {
        loadingSearchModal.show();
    });

    /* SUCCESS */
    const successInput = document.getElementById("success-message");
    if (successInput) {
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;
        setTimeout(() => modal.show(), 250);
    }

});

/* OPEN MODALS */
function openAktifkanModal(action) {
    document.getElementById("aktifkanForm").action = action;
    aktifkanModal.show();
}

function openHapusModal(action) {
    document.getElementById("hapusForm").action = action;
    hapusModal.show();
}

/* SUBMIT ACTION */
document.addEventListener("submit", function (e) {
    if (e.target.id === "aktifkanForm" || e.target.id === "hapusForm") {
        e.preventDefault();
        aktifkanModal.hide();
        hapusModal.hide();
        loadingActionModal.show();
        setTimeout(() => e.target.submit(), 150);
    }
});
</script>
@endsection
