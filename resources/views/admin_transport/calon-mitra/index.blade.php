@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Calon Mitra</h4>

{{-- ALERT --}}
@if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>No Handphone</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($calonmitra as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->no_handphone }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>
                        @if($item->is_checked)
                            <span class="badge bg-success px-3 py-2 text-light">Disetujui</span>
                        @else
                            <span class="badge bg-warning px-3 py-2 text-light">Belum Diproses</span>
                        @endif
                    </td>
                    <td class="text-center">

                        <a href="{{ url('/calon-mitra/'.$item->id) }}"
                           class="btn btn-info btn-sm btnLaporan">
                            Detail
                        </a>

                        <button type="button"
                            class="btn btn-danger btn-sm"
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
                    <td colspan="6" class="text-center">
                        Data calon mitra tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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

                <h5 class="fw-bold mb-2">Hapus Data Calon mitra?</h5>

                <p class="text-muted mb-4">
                    Calon mitra <strong id="deleteUnitName"></strong> akan dihapus permanen
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
        deleteForm.action = `/calon-mitra/${id}`;
    });

});
</script>


<script>
document.addEventListener("DOMContentLoaded", function(){

    const successInput = document.getElementById("success-message");

    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(() => {
            modal.show();
        }, 250);
    }

});
</script>


<script>
document.addEventListener("DOMContentLoaded", function(){

    const buttons = document.querySelectorAll(".btnLaporan");
    if(!buttons.length) return;

    const modal = new bootstrap.Modal(document.getElementById("loadingModal"));

    buttons.forEach(btn => {
        btn.addEventListener("click", function(e){
            e.preventDefault();
            modal.show();

            setTimeout(() => {
                window.location.href = btn.href;
            }, 150);
        });
    });

});
</script>

@endsection
