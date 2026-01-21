@extends('user.layouts.app')

@section('title', 'Daftar Mitra Transport')

@section('content')

<!-- ===== HEADER / HERO ===== -->
<section class="text-white"
    style="
        background: linear-gradient(rgba(5,10,48,.85), rgba(5,10,48,.85)),
        url('{{ asset('assets/bg-transport.jpg') }}') center/cover no-repeat;
        padding: 140px 0 90px;
    ">
    <div class="container text-center">
        <h1 class="fw-bold mb-3">
            Daftar Mitra Transport
        </h1>
        <p class="mb-0">
            Home / <span class="text-primary">Daftar Mitra</span>
        </p>
    </div>
</section>

<!-- ===== CONTENT ===== -->
<section class="py-5 bg-light">
    <div class="container">

        <div class="row g-4 align-items-stretch">

            <!-- ===== KIRI (GAMBAR + SYARAT) ===== -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow rounded-4 overflow-hidden">

                    <div class="ratio ratio-16x9">
                        <img src="{{ asset('assets/bg-transport.jpg') }}"
                             class="img-fluid object-fit-cover"
                             alt="Transport Image">
                    </div>

                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            Syarat Menjadi Mitra Transport
                        </h5>

                        <p class="text-muted mb-3">
                            Untuk menjaga kualitas layanan dan profesionalisme,
                            calon mitra wajib memenuhi ketentuan berikut:
                        </p>

                        <ul class="text-muted ps-3 mb-0">
                            <li class="mb-2">
                                Terdaftar sebagai <strong>driver aktif Grab, Gojek, atau Maxim</strong>.
                            </li>
                            <li class="mb-2">
                                Bersedia menyiapkan <strong>jaminan sesuai ketentuan perusahaan</strong>.
                            </li>
                            <li class="mb-2">
                                Sanggup memenuhi <strong>setoran harian sebesar Rp200.000</strong>.
                            </li>
                            <li>
                                Memiliki sikap profesional dan bertanggung jawab.
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- ===== KANAN (FORM) ===== -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow rounded-4">

                    <!-- HEADER CARD -->
                    <div class="card-header bg-white text-center py-4">
                        <h4 class="fw-bold mb-1">
                            Form Pendaftaran Mitra Transport
                        </h4>
                        <p class="text-muted mb-0">
                            Bergabunglah sebagai mitra transport profesional
                        </p>
                    </div>

                    <div class="card-body p-5 d-flex flex-column justify-content-center">

                        {{-- ALERT SUCCESS --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- ALERT ERROR --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mitra.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       value="{{ old('nama') }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No Handphone</label>
                                <input type="number"
                                       name="no_handphone"
                                       class="form-control"
                                       value="{{ old('no_handphone') }}"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control"
                                          rows="4"
                                          required>{{ old('alamat') }}</textarea>
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

@endsection
