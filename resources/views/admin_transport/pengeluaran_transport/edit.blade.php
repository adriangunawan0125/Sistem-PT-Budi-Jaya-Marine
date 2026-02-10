@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengeluaran Transport</h4>

     {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- SUCCESS TRIGGER (HIDDEN) --}}
    @if (session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif


    <form  id="editForm" method="POST"
          action="{{ route('pengeluaran_transport.update', $pengeluaran_transport->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Plat Nomor</label>
            <select name="unit_id" class="form-control" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}"
                        {{ $pengeluaran_transport->unit_id == $unit->id ? 'selected' : '' }}>
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
                   value="{{ $pengeluaran_transport->tanggal }}"
                   required>
        </div>

        <h5>Item Pengeluaran</h5>
        <table class="table table-bordered" id="items_table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluaran_transport->items as $item)
                    <tr>
                        <td>
                            <input type="text"
                                   name="keterangan[]"
                                   class="form-control"
                                   value="{{ $item->keterangan }}"
                                   required>
                        </td>

                        <td>
                            <input type="text"
                                   class="form-control rupiah"
                                   value="Rp {{ number_format($item->nominal,0,',','.') }}"
                                   required>
                            <input type="hidden"
                                   name="nominal[]"
                                   value="{{ $item->nominal }}">
                        </td>

                        <td>
                            @if($item->gambar)
                                <div class="mb-1">
                                    <img src="{{ asset('storage/'.$item->gambar) }}"
                                         width="80"
                                         class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file"
                                   name="gambar[]"
                                   class="form-control"
                                   accept="image/*">
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger remove-row">Hapus</button>
                        </td>
                    </tr>
                @empty
                    {{-- Jika tidak ada item, tampilkan 1 row kosong --}}
                    <tr>
                        <td>
                            <input type="text" name="keterangan[]" class="form-control" required>
                        </td>
                        <td>
                            <input type="text" class="form-control rupiah" placeholder="Rp 0" required>
                            <input type="hidden" name="nominal[]" value="0">
                        </td>
                        <td>
                            <input type="file" name="gambar[]" class="form-control" accept="image/*">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Hapus</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary mb-3" id="add_item">Tambah Item</button>
        <br>

        <button type="submit" class="btn btn-primary" onclick="return cekItem()">Update</button>
        <a href="{{ route('pengeluaran_transport.index') }}" class="btn btn-secondary">Batal</a>
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


<script>
document.addEventListener("DOMContentLoaded", function(){

    /* ========= LOADING SUBMIT ========= */
    const form = document.getElementById("editForm");
    const loadingModal = new bootstrap.Modal(document.getElementById("loadingModal"));

    form.addEventListener("submit", function(e){

        e.preventDefault();

        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        loadingModal.show();

        setTimeout(() => {
            form.submit();
        }, 150);
    });


    /* ========= SUCCESS AFTER REDIRECT ========= */
    const successInput = document.getElementById("success-message");

    if(successInput){
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        document.getElementById("successText").innerText = successInput.value;

        setTimeout(() => {
            modal.show();
        }, 250);
    }

});
</script>

{{-- ================= JS ================= --}}
<script>
function cekItem() {
    let rows = document.querySelectorAll('#items_table tbody tr');
    if (rows.length === 0) {
        alert('Minimal harus ada 1 item pengeluaran');
        return false;
    }

    let valid = true;
    document.querySelectorAll('input[name="nominal[]"]').forEach(el => {
        if (!el.value || el.value == 0) {
            valid = false;
        }
    });

    if (!valid) {
        alert('Nominal tidak boleh kosong');
        return false;
    }

    return true;
}

// ================= FORMAT RUPIAH =================
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

function bindRupiah(input) {
    input.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        raw = raw.replace(/^0+/, '');
        this.value = raw ? formatRupiah(raw) : '';
        this.nextElementSibling.value = raw || 0;
    });
}

document.querySelectorAll('.rupiah').forEach(bindRupiah);

// ================= TAMBAH ROW =================
document.getElementById('add_item').addEventListener('click', function () {
    let tbody = document.querySelector('#items_table tbody');
    let row = document.createElement('tr');

    row.innerHTML = `
        <td><input type="text" name="keterangan[]" class="form-control" required></td>
        <td>
            <input type="text" class="form-control rupiah" placeholder="Rp 0" required>
            <input type="hidden" name="nominal[]" value="0">
        </td>
        <td><input type="file" name="gambar[]" class="form-control" accept="image/*"></td>
        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
    `;

    tbody.appendChild(row);
    bindRupiah(row.querySelector('.rupiah'));
});

// ================= HAPUS ROW =================
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
