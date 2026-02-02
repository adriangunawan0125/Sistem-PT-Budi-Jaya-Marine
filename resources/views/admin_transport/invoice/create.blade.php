@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Buat Invoice</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data">
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
        <table class="table" id="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Tanggal TF</th>
                    <th>Cicilan</th>
                    <th>Tagihan</th>
                    <th>Bukti Transfer</th>
                    <th>Bukti Perjalanan</th>
                    <th width="60"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input name="items[0][item]" class="form-control" required>
                    </td>

                    <td>
                        <input type="date" name="items[0][tanggal]" class="form-control">
                    </td>

                    {{-- CICILAN --}}
                    <td>
                        <input type="text" class="form-control rupiah"
                               data-hidden="items[0][cicilan]"
                               placeholder="Rp 0">
                        <input type="hidden" name="items[0][cicilan]" value="0">
                    </td>

                    {{-- TAGIHAN --}}
                    <td>
                        <input type="text" class="form-control rupiah"
                               data-hidden="items[0][tagihan]"
                               placeholder="Rp 0">
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

        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addItem()">+ Item</button>

        <hr>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('invoice.index')}}"
               class="btn btn-secondary mr-2">
                Batal
            </a>
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

    return rupiah ? 'Rp ' + rupiah : 'Rp 0';
}

function bindRupiah(el) {
    el.addEventListener('input', function () {
        // ambil angka murni
        let raw = this.value.replace(/\D/g, '');

        // HILANGKAN LEADING ZERO
        raw = raw.replace(/^0+/, '');

        // kalau kosong, biarin kosong (jangan Rp 0 dulu)
        if (raw === '') {
            this.value = '';
        } else {
            this.value = formatRupiah(raw);
        }

        // isi hidden input
        let hiddenName = this.dataset.hidden;
        let hiddenInput = this.parentElement.querySelector(
            `input[name="${hiddenName}"]`
        );
        hiddenInput.value = raw === '' ? 0 : raw;
    });
}


document.querySelectorAll('.rupiah').forEach(el => bindRupiah(el));

/* ================= ADD ITEM ================= */
function addItem() {
    let row = `
    <tr>
        <td>
            <input name="items[${i}][item]" class="form-control" required>
        </td>

        <td>
            <input type="date" name="items[${i}][tanggal]" class="form-control">
        </td>

        <td>
            <input type="text" class="form-control rupiah"
                   data-hidden="items[${i}][cicilan]"
                   placeholder="Rp 0">
            <input type="hidden" name="items[${i}][cicilan]" value="0">
        </td>

        <td>
            <input type="text" class="form-control rupiah"
                   data-hidden="items[${i}][tagihan]"
                   placeholder="Rp 0">
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
            <button type="button" class="btn btn-danger text-light" onclick="this.closest('tr').remove()">hapus</button>
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
</script>
@endsection
