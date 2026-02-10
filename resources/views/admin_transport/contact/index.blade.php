@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Pesan Hubungi Kami</h4>

    @if(session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif

    {{-- SEARCH --}}
    <form method="GET" class="row g-2 mb-3" id="filterForm">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama pengirim..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('contact.index') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Telepon</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_telepon ?? '-' }}</td>
                    <td class="text-center">

                        <a href="{{ route('contact.show', $item->id) }}"
                           class="btn btn-info btn-sm btnDetail">
                           Detail
                        </a>

                        <button class="btn btn-danger btn-sm ms-1"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->nama }}">
                            Hapus
                        </button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Data pesan tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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

                <h5 class="fw-bold mb-2">Hapus Pesan?</h5>

                <p class="text-muted mb-4">
                    Pesan dari <strong id="deleteName"></strong> akan dihapus permanen.
                </p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-secondary px-4"
                                data-bs-dismiss="modal" style="margin-right: 7px">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-danger px-4">
                            Hapus
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


<script>
document.addEventListener("DOMContentLoaded", function(){

    const loading = new bootstrap.Modal(document.getElementById("loadingModal"));

    // SUCCESS MODAL
    const successInput = document.getElementById("success-message");
    if(successInput){
        const successModal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(()=>{
            successModal.show();
        },250);
    }

    // FILTER LOADING
    const filterForm = document.getElementById("filterForm");
    if(filterForm){
        filterForm.addEventListener("submit", function(e){
            e.preventDefault();
            loading.show();

            setTimeout(() => {
                filterForm.submit();
            }, 150);
        });
    }

    // DETAIL LOADING
    document.querySelectorAll(".btnDetail").forEach(btn=>{
        btn.addEventListener("click", function(e){
            e.preventDefault();
            loading.show();

            setTimeout(()=>{
                window.location.href = btn.href;
            },150);
        });
    });

    // DELETE ACTION SETTER
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm  = document.getElementById('deleteForm');
    const deleteName  = document.getElementById('deleteName');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const name   = button.getAttribute('data-name');

        deleteName.textContent = name;
        deleteForm.action = `/contact/${id}`;
    });

});
</script>

@endsection
