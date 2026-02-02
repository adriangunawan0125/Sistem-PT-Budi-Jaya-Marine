@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Pajak</h4>

    <form method="POST"
          action="{{ route('pengeluaran_pajak.update', $pengeluaranPajak->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Plat Nomor</label>
            <select name="unit_id" class="form-control" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}"
                        {{ $pengeluaranPajak->unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="{{ $pengeluaranPajak->tanggal }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <input type="text"
                   name="deskripsi"
                   class="form-control"
                   value="{{ $pengeluaranPajak->deskripsi }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="text"
                   class="form-control rupiah"
                   value="Rp {{ number_format($pengeluaranPajak->nominal, 0, ',', '.') }}"
                   placeholder="Rp 0"
                   required>
            <input type="hidden"
                   name="nominal"
                   value="{{ $pengeluaranPajak->nominal }}">
        </div>

        <div class="mb-3">
            <label>Gambar</label>
            @if($pengeluaranPajak->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$pengeluaranPajak->gambar) }}"
                         alt="Gambar"
                         width="120"
                         class="img-thumbnail">
                </div>
            @endif
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">
            <small class="text-muted">
                Opsional, pilih jika ingin mengganti gambar
            </small>
        </div>

        <button type="submit" class="btn btn-primary">
            Update
        </button>
         <a href="{{ route('pengeluaran_pajak.index') }}"
           class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>

<script>
// ================= RUPIAH =================
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

function bindRupiah(el) {
    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');

        this.value = raw ? formatRupiah(raw) : '';
        this.nextElementSibling.value = raw || 0;
    });
}

document.querySelectorAll('.rupiah').forEach(bindRupiah);
</script>
@endsection
