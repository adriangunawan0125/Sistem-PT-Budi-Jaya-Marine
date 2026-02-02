@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Internal</h4>

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
          action="{{ route('pengeluaran_internal.update', $pengeluaranInternal->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ $pengeluaranInternal->tanggal }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ $pengeluaranInternal->deskripsi }}"
                   required>
        </div>

        {{-- NOMINAL (RUPIAH – EDIT MODE FIX) --}}
        <div class="mb-3">
            <label class="form-label">Nominal</label>

            <input type="text"
                   class="form-control rupiah"
                   data-hidden="nominal"
                   value="Rp {{ number_format($pengeluaranInternal->nominal, 0, ',', '.') }}"
                   required>

            <input type="hidden"
                   name="nominal"
                   value="{{ $pengeluaranInternal->nominal }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar (Bukti)</label>

            @if($pengeluaranInternal->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$pengeluaranInternal->gambar) }}"
                         width="120"
                         class="d-block mb-2">
                </div>
            @endif

            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('pengeluaran_internal.index') }}"
           class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>

{{-- ================= JS RUPIAH (KONSISTEN, NO 02) ================= --}}
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

        let hiddenName = this.dataset.hidden;
        document.querySelector(`input[name="${hiddenName}"]`).value = raw || 0;
    });
});
</script>
@endsection
