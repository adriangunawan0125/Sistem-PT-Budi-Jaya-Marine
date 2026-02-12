@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Mitra Marine</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('mitra-marine.update', $mitra->id) }}"
          method="POST"
          onsubmit="return submitWithLoading()">
        @csrf

        {{-- DATA PERUSAHAAN --}}
        <div class="mb-3">
            <label>Nama Perusahaan</label>
            <input type="text" name="nama_mitra" class="form-control"
                   value="{{ $mitra->nama_mitra }}" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="address" class="form-control" rows="2">{{ $mitra->address }}</textarea>
        </div>

        <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telp" class="form-control"
                   value="{{ $mitra->telp }}">
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

                    {{-- VESSEL LAMA --}}
                    @foreach($mitra->vessels as $key => $v)
                    <tr>
                        <td>
                            <input type="text"
                                   name="vessel_existing[{{ $v->id }}]"
                                   class="form-control"
                                   value="{{ $v->nama_vessel }}">
                        </td>
                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="removeExisting(this, {{ $v->id }})">
                                hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <button type="button"
                class="btn btn-sm btn-secondary mt-3 mb-3"
                onclick="addVessel()">
            + Vessel Baru
        </button>

        <input type="hidden" name="deleted_vessel" id="deleted_vessel">

        <hr>

        <button class="btn btn-primary">Update</button>
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
                <div class="fw-semibold">Mengupdate data...</div>
            </div>
        </div>
    </div>
</div>


<script>
let newIndex = 0;
let deleted = [];

/* TAMBAH VESSEL BARU */
function addVessel(){
    let row = `
    <tr>
        <td>
            <input type="text" name="vessel_new[${newIndex}]" class="form-control">
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

    newIndex++;
}

/* HAPUS VESSEL LAMA */
function removeExisting(btn, id){
    deleted.push(id);
    document.getElementById('deleted_vessel').value = deleted.join(',');
    btn.closest('tr').remove();
}

/* LOADING */
function submitWithLoading(){
    let modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();
    return true;
}
</script>
@endsection
