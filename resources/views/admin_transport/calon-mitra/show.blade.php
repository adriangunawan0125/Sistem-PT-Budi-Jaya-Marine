@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Detail Calon Mitra</h4>

    <table class="table table-bordered mb-4">
        <tr>
            <th width="200">Nama</th>
            <td>{{ $calonmitra->nama }}</td>
        </tr>
        <tr>
            <th>No Handphone</th>
            <td>{{ $calonmitra->no_handphone }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $calonmitra->alamat }}</td>
        </tr>
        <tr>
            <th>Jaminan</th>
            <td>{{ $calonmitra->jaminan }}</td>
        </tr>
        <tr>
            <th>Tanggal Daftar</th>
            <td>{{ $calonmitra->created_at->format('d M Y') }}</td>
        </tr>
    </table>

    {{-- GAMBAR JAMINAN --}}
    <div class="row mb-4">
        @if($calonmitra->gambar_1)
        <div class="col-md-4 mb-3">
            <p class="fw-bold">Gambar Jaminan 1</p>
            <img src="{{ asset('storage/'.$calonmitra->gambar_1) }}"
                 class="img-fluid img-thumbnail">
        </div>
        @endif

        @if($calonmitra->gambar_2)
        <div class="col-md-4 mb-3">
            <p class="fw-bold">Gambar Jaminan 2</p>
            <img src="{{ asset('storage/'.$calonmitra->gambar_2) }}"
                 class="img-fluid img-thumbnail">
        </div>
        @endif

        @if($calonmitra->gambar_3)
        <div class="col-md-4 mb-3">
            <p class="fw-bold">Gambar Jaminan 3</p>
            <img src="{{ asset('storage/'.$calonmitra->gambar_3) }}"
                 class="img-fluid img-thumbnail">
        </div>
        @endif
    </div>

{{-- AKSI --}}
<div class="d-flex mt-4">

    <a href="{{ url('/calon-mitra') }}"
       class="btn btn-secondary">
        Kembali
    </a>

    @if(!$calonmitra->is_checked)
        <form id="approveForm"
              action="{{ url('/calon-mitra/'.$calonmitra->id.'/approve') }}"
              method="POST"
              style="margin-left:16px;">
            @csrf

            <button type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#approveModal">
                Setujui Jadi Mitra
            </button>
        </form>
    @else
       <button type="button"
        class="btn btn-success ms-3"
        style="margin-left:16px;"
        disabled>
    Sudah Disetujui
</button>
    @endif

</div>

</div>


<!-- APPROVE MODAL -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Setujui Calon Mitra?</h5>

                <p class="text-muted mb-4">
                    Calon mitra <strong>{{ $calonmitra->nama }}</strong> ini akan dijadikan mitra aktif.
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button"
                            class="btn btn-secondary px-4" style="margin-right:7px"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="button"
                            class="btn btn-success px-4"
                            id="btnApprove">
                        Setujui
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("approveForm");
    if(!form) return;

    const approveModal = bootstrap.Modal.getOrCreateInstance(document.getElementById("approveModal"));
    const btnApprove = document.getElementById("btnApprove");

    btnApprove.addEventListener("click", function(){
        approveModal.hide();
        form.submit();
    });

});
</script>

@endsection
