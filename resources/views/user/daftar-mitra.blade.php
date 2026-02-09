@extends('user.layouts.app')

@section('title', 'Daftar Mitra Transport')

@section('content')

<style>
    /* CENTER SPINNER DI MODAL */
#loadingModal .modal-content{
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    height:180px;
}

#loadingModal .spinner-border{
    display:block;
    margin:0 auto 15px auto;
}
</style>
<!-- ===== HEADER / HERO ===== -->
<section class="text-white"
    style="
        background: linear-gradient(rgba(5,10,48,.85), rgba(5,10,48,.85)),
        url('{{ asset('assets/bg-transport.jpg') }}') center/cover no-repeat;
        padding: 140px 0 90px;
    ">
    <div class="container text-center">
        <h1 class="fw-bold mb-3">Daftar Mitra Transport</h1>
        <p class="mb-0">
            Home / <span class="text-primary">Daftar Mitra</span>
        </p>
    </div>
</section>

<!-- ===== CONTENT ===== -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 align-items-stretch">

            <!-- ===== KIRI ===== -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow rounded-4 overflow-hidden">
                    <div class="ratio ratio-16x9">
                        <img src="{{ asset('assets/bg-transport.jpg') }}"
                             class="img-fluid object-fit-cover">
                    </div>

                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Syarat Menjadi Mitra Transport</h5>
                        <ul class="text-muted ps-3 mb-0">
                            <li class="mb-2">Driver aktif Grab / Gojek / Maxim</li>
                            <li class="mb-2">Menyiapkan jaminan sesuai ketentuan</li>
                            <li class="mb-2">Setoran harian Rp200.000</li>
                            <li>Bertanggung jawab & profesional</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ===== KANAN (FORM) ===== -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow rounded-4">
                    <div class="card-header bg-white text-center py-4">
                        <h4 class="fw-bold mb-1">Form Pendaftaran Mitra Transport</h4>
                        <p class="text-muted mb-0">Bergabung sebagai mitra profesional</p>
                    </div>

                    <div class="card-body p-5">

                        {{-- ALERT --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST"
                              action="{{ route('mitra.store') }}"
                              enctype="multipart/form-data"
                              id="formMitra">
                            @csrf

                            <!-- NAMA -->
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       value="{{ old('nama') }}"
                                       required>
                            </div>

                            <!-- NO HP -->
                            <div class="mb-3">
                                <label class="form-label">No Handphone</label>
                                <input type="number"
                                       name="no_handphone"
                                       class="form-control"
                                       value="{{ old('no_handphone') }}"
                                       required>
                            </div>

                            <!-- ALAMAT -->
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control"
                                          rows="3"
                                          required>{{ old('alamat') }}</textarea>
                            </div>

                            <!-- JAMINAN TEXT -->
                            <div class="mb-3">
                                <label class="form-label">Jaminan</label>
                                <input type="text"
                                       name="jaminan"
                                       class="form-control"
                                       placeholder="Contoh: STNK / STNK + KTP / STNK + KTP + BPKB"
                                       value="{{ old('jaminan') }}"
                                       required>
                            </div>

                            <!-- GAMBAR JAMINAN -->
                            <div class="mb-3">
                                <label class="form-label">Upload Jaminan 1 (Wajib)</label>
                                <input type="file"
                                       name="gambar_1"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Jaminan 2 (Opsional)</label>
                                <input type="file"
                                       name="gambar_2"
                                       class="form-control">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Upload Jaminan 3 (Opsional)</label>
                                <input type="file"
                                       name="gambar_3"
                                       class="form-control">
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary px-5 py-2">
                                    Daftar Mitra
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ================= LOADING MODAL ================= -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
            <h5 class="mb-0">Mengirim pendaftaran...</h5>
        </div>
    </div>
</div>

<!-- ================= SUCCESS MODAL ================= -->
<div class="modal fade" id="successModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="text-success mb-2" style="font-size:55px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h5 class="mb-2">Pendaftaran Berhasil</h5>
            <div>{{ session('success') }}</div>
            <button class="btn btn-primary mt-3" data-bs-dismiss="modal">OK</button>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function(){

    // tampilkan loading saat submit
    const form = document.getElementById("formMitra");
    if(form){
        form.addEventListener("submit", function(){
            let modal = new bootstrap.Modal(document.getElementById('loadingModal'));
            modal.show();
        });
    }

    // tampilkan success setelah redirect
    @if(session('success'))
        let successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    @endif

});
</script>

@endsection
