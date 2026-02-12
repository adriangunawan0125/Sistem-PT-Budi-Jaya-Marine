@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Mitra Marine</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('mitra-marine.store') }}"
          method="POST"
          onsubmit="return submitWithLoading()">
        @csrf

        {{-- DATA PERUSAHAAN --}}
        <div class="mb-3">
            <label>Nama Perusahaan</label>
            <input type="text" name="nama_mitra" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="address" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telp" class="form-control">
        </div>

        <hr>

        <h5>Daftar Kapal (Vessel)</h5>

        <div class="table-responsive">
            <table class="table table-bordered" id="vessels">
                <thead class="table-light">
                    <tr>
                        <th>Nama Vessel</th>
                        <th style="width:70px"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="vessel[0]" class="form-control">
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="button"
                class="btn btn-sm btn-secondary mt-3 mb-3"
                onclick="addVessel()">
            + Vessel
        </button>

        <hr>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('mitra-marine.index') }}" class="btn btn-secondary">Batal</a>

    </form>
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
                <div class="fw-semibold">Menyimpan data...</div>
            </div>
        </div>
    </div>
</div>


<script>
let i = 1;

/* TAMBAH BARIS VESSEL */
function addVessel(){
    let row = `
    <tr>
        <td>
            <input type="text" name="vessel[${i}]" class="form-control">
        </td>
        <td class="text-center">
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove()">
                hapus
            </button>
        </td>
    </tr>`;

    document.querySelector('#vessels tbody')
        .insertAdjacentHTML('beforeend', row);

    i++;
}

/* SUBMIT + LOADING */
function submitWithLoading(){
    let modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();
    return true;
}
</script>
@endsection
