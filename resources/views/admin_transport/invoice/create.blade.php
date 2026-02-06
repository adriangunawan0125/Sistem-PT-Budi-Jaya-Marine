@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Buat Invoice</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return cekItem()">
        @csrf

        {{-- MITRA --}}
        @if(isset($mitra))
            <input type="hidden" name="mitra_id" value="{{ $mitra->id }}">
            <div class="mb-3">
                <label>Mitra</label>
                <input type="text" class="form-control" value="{{ $mitra->nama_mitra }}" readonly>
            </div>
        @else
            <div class="mb-3">
                <label>Mitra</label>
                <select name="mitra_id" class="form-control" required>
                    <option value="">-- Pilih Mitra --</option>
                    @foreach($mitras as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_mitra }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <hr>

        {{-- ITEM INVOICE --}}
        <h5>Item Invoice</h5>
        <div class="table-responsive">
            <table class="table table-bordered" id="items">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 180px;">No Invoice</th>
                        <th style="min-width: 140px;">Tanggal Invoice</th>
                        <th style="min-width: 220px;">Item</th>
                        <th style="min-width: 140px;">Tanggal TF</th>
                        <th style="min-width: 140px;">Cicilan</th>
                        <th style="min-width: 140px;">Tagihan</th>
                        <th style="min-width: 140px;">Bukti Transfer</th>
                        <th style="min-width: 140px;">Bukti Perjalanan</th>
                        <th style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input name="items[0][no_invoices]" class="form-control" style="width:100%;"></td>
                        <td><input type="date" name="items[0][tanggal_invoices]" class="form-control" style="width:100%;"></td>
                        <td><input name="items[0][item]" class="form-control" style="width:100%;" required></td>
                        <td><input type="date" name="items[0][tanggal_tf]" class="form-control" style="width:100%;"></td>

                        <td>
                            <input type="text" class="form-control rupiah" data-hidden="items[0][cicilan]" placeholder="Rp 0" style="width:100%;">
                            <input type="hidden" name="items[0][cicilan]" value="0">
                        </td>

                        <td>
                            <input type="text" class="form-control rupiah" data-hidden="items[0][tagihan]" placeholder="Rp 0" style="width:100%;">
                            <input type="hidden" name="items[0][tagihan]" value="0">
                        </td>

                        <td>
                            <input type="file" name="items[0][gambar_transfer]" class="form-control" accept="image/*"
                                   onchange="previewItemImage(this, 'transferPreview0')">
                            <div id="transferPreview0" class="mt-2 d-flex gap-2 flex-wrap"></div>
                        </td>

                        <td>
                            <input type="file" name="items[0][gambar_trip]" class="form-control" accept="image/*"
                                   onchange="previewItemImage(this, 'tripPreview0')">
                            <div id="tripPreview0" class="mt-2 d-flex gap-2 flex-wrap"></div>
                        </td>

                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-sm btn-secondary mb-3 mt-3" onclick="addItem()">+ Item</button>

        <hr>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('invoice.index')}}" class="btn btn-secondary mr-2">Batal</a>
    </form>
</div>

<script>
let i = 1;

/* ================= RUPIAH FORMAT ================= */
function formatRupiah(angka) {
    let number_string = angka.replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

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
        let hiddenName = this.dataset.hidden;
        let hiddenInput = this.parentElement.querySelector(`input[name="${hiddenName}"]`);
        hiddenInput.value = raw === '' ? 0 : raw;
    });
}

document.querySelectorAll('.rupiah').forEach(el => bindRupiah(el));

/* ================= ADD ITEM ================= */
function addItem() {
    let row = `
    <tr>
        <td><input name="items[${i}][no_invoices]" class="form-control" style="width:100%;"></td>
        <td><input type="date" name="items[${i}][tanggal_invoices]" class="form-control" style="width:100%;"></td>
        <td><input name="items[${i}][item]" class="form-control" style="width:100%;" required></td>
        <td><input type="date" name="items[${i}][tanggal_tf]" class="form-control" style="width:100%;"></td>

        <td>
            <input type="text" class="form-control rupiah" data-hidden="items[${i}][cicilan]" placeholder="Rp 0" style="width:100%;">
            <input type="hidden" name="items[${i}][cicilan]" value="0">
        </td>

        <td>
            <input type="text" class="form-control rupiah" data-hidden="items[${i}][tagihan]" placeholder="Rp 0" style="width:100%;">
            <input type="hidden" name="items[${i}][tagihan]" value="0">
        </td>

        <td>
            <input type="file" name="items[${i}][gambar_transfer]" class="form-control" accept="image/*"
                   onchange="previewItemImage(this, 'transferPreview${i}')">
            <div id="transferPreview${i}" class="mt-2 d-flex gap-2 flex-wrap"></div>
        </td>

        <td>
            <input type="file" name="items[${i}][gambar_trip]" class="form-control" accept="image/*"
                   onchange="previewItemImage(this, 'tripPreview${i}')">
            <div id="tripPreview${i}" class="mt-2 d-flex gap-2 flex-wrap"></div>
        </td>

        <td>
            <button type="button" class="btn btn-danger text-light btn-sm" onclick="this.closest('tr').remove()">hapus</button>
        </td>
    </tr>`;

    document.querySelector('#items tbody').insertAdjacentHTML('beforeend', row);
    document.querySelectorAll('.rupiah').forEach(el => bindRupiah(el));
    i++;
}

/* ================= PREVIEW IMAGE ================= */
function previewItemImage(input, previewId) {
    const container = document.getElementById(previewId);
    container.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                img.classList.add('border','p-1','rounded');
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}

/* ================= CEK ITEM ================= */
function cekItem() {
    const rows = document.querySelectorAll('#items tbody tr');
    if (rows.length === 0) {
        alert('Minimal harus ada 1 item invoice');
        return false;
    }

    let valid = true;
    document.querySelectorAll('input[name*="[tagihan]"]').forEach(el => {
        if (!el.value || el.value == 0) valid = false;
    });

    if (!valid) {
        alert('Tagihan wajib diisi dan tidak boleh 0');
        return false;
    }
    return true;
}
</script>
@endsection
