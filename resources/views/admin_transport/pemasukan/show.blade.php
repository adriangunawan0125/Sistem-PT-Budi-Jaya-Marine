@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Detail Pemasukan Transport</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Tanggal</div>
                <div class="col-md-9">
                    {{ \Carbon\Carbon::parse($pemasukan->tanggal)->format('d-m-Y') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Mitra</div>
                <div class="col-md-9">
                    {{ $pemasukan->mitra->nama_mitra ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Kategori</div>
                <div class="col-md-9">
                    {{ ucfirst($pemasukan->kategori) }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Deskripsi</div>
                <div class="col-md-9">
                    {{ $pemasukan->deskripsi }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nominal</div>
                <div class="col-md-9">
                    <span class="fw-bold text-success">
                        Rp {{ number_format($pemasukan->nominal,0,',','.') }}
                    </span>
                </div>
            </div>

            {{-- ================= BUKTI TRANSFER ================= --}}
            <div class="row mb-4">
                <div class="col-md-3 fw-bold">Bukti Transfer</div>
                <div class="col-md-9">

                    @if($pemasukan->gambar || $pemasukan->gambar1)

                        <div class="row g-3">

                            @if($pemasukan->gambar)
                                <div class="col-md-6 text-center">
                                    <p class="small text-muted mb-2">Bukti TF 1</p>
                                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}"
                                         class="img-fluid rounded shadow preview-img"
                                         style="max-height:300px; cursor:pointer;"
                                         onclick="showImage(this.src)">
                                </div>
                            @endif

                            @if($pemasukan->gambar1)
                                <div class="col-md-6 text-center">
                                    <p class="small text-muted mb-2">Bukti TF 2</p>
                                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar1) }}"
                                         class="img-fluid rounded shadow preview-img"
                                         style="max-height:300px; cursor:pointer;"
                                         onclick="showImage(this.src)">
                                </div>
                            @endif

                        </div>

                    @else
                        <span class="text-muted">Tidak ada gambar</span>
                    @endif

                </div>
            </div>

            <a href="{{ route('pemasukan.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>

</div>


{{-- ================= MODAL FULLSCREEN ================= --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark text-center">

            <div class="modal-header border-0">
                <button type="button"
                        class="btn-close btn-close-white ms-auto"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body d-flex align-items-center justify-content-center">
                <img id="modalImage"
                     src=""
                     class="img-fluid"
                     style="max-height:95vh;">
            </div>

        </div>
    </div>
</div>

<script>
function showImage(src){
    document.getElementById('modalImage').src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>

@endsection