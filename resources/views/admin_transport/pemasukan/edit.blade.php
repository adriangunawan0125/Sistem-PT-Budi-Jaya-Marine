@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Pemasukan Transport</h4>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editForm" action="{{ route('pemasukan.update', $pemasukan->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- MITRA (READ ONLY) --}}
        <div class="mb-3">
            <label class="form-label">Nama Mitra</label>
            <input type="text"
                   class="form-control"
                   value="{{ $pemasukan->mitra->nama_mitra ?? '-' }}"
                   disabled>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ old('tanggal', \Carbon\Carbon::parse($pemasukan->tanggal)->format('Y-m-d')) }}"
                   required>
        </div>

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="setoran" {{ old('kategori', $pemasukan->kategori)=='setoran'?'selected':'' }}>Setoran</option>
                <option value="cicilan" {{ old('kategori', $pemasukan->kategori)=='cicilan'?'selected':'' }}>Cicilan</option>
                <option value="deposit" {{ old('kategori', $pemasukan->kategori)=='deposit'?'selected':'' }}>Deposit</option>
            </select>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ old('deskripsi', $pemasukan->deskripsi) }}"
                   required>
        </div>

        {{-- NOMINAL --}}
        <div class="mb-3">
            <label class="form-label">Nominal</label>

            <input type="text"
                   class="form-control rupiah"
                   data-hidden="nominal"
                   placeholder="Rp 0"
                   value="{{ old('nominal')
                        ? 'Rp ' . number_format(old('nominal'), 0, ',', '.')
                        : 'Rp ' . number_format($pemasukan->nominal, 0, ',', '.') }}">

            <input type="hidden"
                   name="nominal"
                   value="{{ old('nominal', $pemasukan->nominal) }}">
        </div>

        {{-- GAMBAR --}}
        <div class="mb-3">
            <label class="form-label">Ganti Gambar (Opsional)</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*"
                   onchange="previewImage(event)">

            <img id="preview" class="img-thumbnail mt-2" width="150" style="display:none;">

            @if ($pemasukan->gambar)
                <div class="mt-3">
                    <p class="mb-1 text-muted">Gambar saat ini:</p>
                    <img src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}"
                         width="150"
                         class="img-thumbnail">
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <div class="fw-semibold">Memperbarui data...</div>
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
                    <button class="btn btn-success px-4" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Rupiah Script --}}
<script>
function formatRupiah(angka) {
    let number_string = angka.replace(/\D/g, ''),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah ? 'Rp ' + rupiah : '';
}

document.querySelectorAll('.rupiah').forEach(el => {
    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');

        this.value = raw ? formatRupiah(raw) : '';
        document.querySelector('input[name="'+this.dataset.hidden+'"]').value = raw || 0;
    });
});
</script>

{{-- LOADING SUBMIT SCRIPT (INI KUNCI NYA) --}}
<script>
document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();

    let modal = new bootstrap.Modal(
        document.getElementById('loadingModal')
    );
    modal.show();

    setTimeout(() => {
        this.submit();
    }, 200);
});
</script>

@endsection
