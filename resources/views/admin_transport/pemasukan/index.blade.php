@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-5">Rekap Pemasukan Transport (Harian)</h4>

    {{-- FILTER --}}
    <form method="GET" class="mb-3">

        <div class="d-flex flex-wrap align-items-end" style="margin-right: 7px;">

            {{-- TANGGAL --}}
            <div class="me-3 mb-2" style="margin-right: 7px;">
                <label class="form-label mb-1">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ request('tanggal', date('Y-m-d')) }}"
                       class="form-control"
                       style="width:170px">
            </div>

            {{-- MITRA --}}
            <div class="me-3 mb-2" style="margin-right: 7px;">
                <label class="form-label mb-1">Mitra</label>
                <input type="text"
                       name="nama"
                       value="{{ request('nama') }}"
                       placeholder="Cari nama mitra"
                       class="form-control"
                       style="width:220px">
            </div>

            {{-- KATEGORI --}}
            <select name="kategori"
                class="form-control d-inline-block me-2 mb-2"
                style="width:160px; margin-right:20px;">
                <option value="">Semua Kategori</option>
                <option value="setoran" {{ request('kategori')=='setoran'?'selected':'' }}>Setoran</option>
                <option value="cicilan" {{ request('kategori')=='cicilan'?'selected':'' }}>Cicilan</option>
                <option value="deposit" {{ request('kategori')=='deposit'?'selected':'' }}>Deposit</option>
            </select>

            {{-- CHECKBOX --}}
            <div class="form-check me-3 mb-2" style="margin-right: 40px;">
                <input class="form-check-input"
                       type="checkbox"
                       name="tidak_setor"
                       value="1"
                       {{ request('tidak_setor')?'checked':'' }}>
                <label class="form-check-label">Tidak TF</label>
            </div>

            {{-- BUTTON GROUP --}}
            <div class="mb-2" style="margin-left:10px">
                <button class="btn btn-primary trigger-loading">Filter</button>
                <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">Reset</a>
                <a href="{{ route('pemasukan.create') }}" class="btn btn-primary">Tambah</a>
                <a href="{{ route('pemasukan.laporan.harian', ['tanggal'=>request('tanggal')]) }}"
                   class="btn btn-info text-white trigger-loading">
                    Laporan Harian
                </a>
            </div>

        </div>

    </form>

      {{-- CARD MITRA TIDAK SETOR (OTOMATIS MUNCUL) --}}
    @if(isset($mitraKosong))
    <div class="card border-danger mb-3">
        <div class="card-header bg-danger text-white">
            Mitra Tidak TF ({{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }})
        </div>
        <div class="card-body">
            @forelse($mitraKosong as $m)
                <span class="badge bg-secondary text-light me-1 mb-1">
                    {{ $m->nama_mitra }}
                </span>
            @empty
                <span class="text-muted">Semua mitra sudah setor</span>
            @endforelse
        </div>
    </div>
    @endif


    {{-- TABLE --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Mitra</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Nominal</th>
            <th width="130">Aksi</th>
        </tr>
        </thead>

        <tbody>
        @forelse($pemasukan as $item)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $item->mitra->nama_mitra ?? '-' }}</td>
            <td class="text-center">{{ ucfirst($item->kategori) }}</td>
            <td>{{ $item->deskripsi }}</td>

            <td class="text-center">
                @if($item->gambar)
                    <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                         width="65"
                         class="img-thumbnail">
                @else
                    -
                @endif
            </td>

            <td><b>Rp {{ number_format($item->nominal,0,',','.') }}</b></td>

            <td class="text-center">
                <a href="{{ route('pemasukan.edit',$item->id) }}"
                   class="btn btn-sm btn-warning mb-1">
                    Edit
                </a>

                <button type="button"
                        class="btn btn-sm btn-danger mb-1"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->mitra->nama_mitra ?? '-' }} ({{ ucfirst($item->kategori) }})">
                    Hapus
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
        </tbody>
    </table>

</div>

{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Hapus Data Pemasukan?</h5>

                <p class="text-muted mb-4">
                    Data <strong id="deleteName"></strong> akan dihapus permanen
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

{{-- ================= LOADING MODAL ================= --}}
<div class="modal fade" id="loadingModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3"
                     style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memuat data...</div>
            </div>
        </div>
    </div>
</div>
<!-- ================= SUCCESS MODAL ================= -->
@if (session('success'))
    <input type="hidden" id="success-message" value="{{ session('success') }}">
@endif

<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center py-4">

                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success"
                       style="font-size:60px;"></i>
                </div>

                <h5 class="fw-bold mb-2">Berhasil</h5>
                <div id="successText" class="text-muted"></div>

                <div class="mt-4">
                    <button class="btn btn-primary px-4"
                            data-bs-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // LOADING
    const loadingModal = new bootstrap.Modal(
        document.getElementById('loadingModal')
    );

    document.querySelectorAll('.trigger-loading').forEach(el => {
        el.addEventListener('click', function () {
            loadingModal.show();
        });
    });

    // DELETE MODAL
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm  = document.getElementById('deleteForm');
    const deleteName  = document.getElementById('deleteName');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const name   = button.getAttribute('data-name');

        deleteName.textContent = name;
        deleteForm.action = `/pemasukan/${id}`;
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ================= SUCCESS MODAL =================
    const successInput = document.getElementById('success-message');
    if (successInput) {
        const successModal = new bootstrap.Modal(
            document.getElementById('successModal')
        );

        document.getElementById('successText').innerText =
            successInput.value;

        setTimeout(() => {
            successModal.show();
        }, 200);
    }

});
</script>


@endsection
