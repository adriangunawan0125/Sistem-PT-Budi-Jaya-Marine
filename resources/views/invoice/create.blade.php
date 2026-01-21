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

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <hr>

        {{-- ITEM INVOICE --}}
        <h5>Item Invoice</h5>
        <table class="table" id="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Cicilan</th>
                    <th>Tagihan</th>
                    <th>Bukti Transfer</th>
                    <th>Bukti Perjalanan</th>
                    <th width="60"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input name="items[0][item]" class="form-control" required></td>
                    <td><input name="items[0][cicilan]" class="form-control" value="0" type="number"></td>
                    <td><input name="items[0][tagihan]" class="form-control" value="0" type="number"></td>
                    <td>
                        <input type="file" name="items[0][gambar_transfer]" class="form-control" accept="image/*" onchange="previewItemImage(this, 'transferPreview0')">
                        <div id="transferPreview0" class="mt-2 d-flex gap-2 flex-wrap"></div>
                    </td>
                    <td>
                        <input type="file" name="items[0][gambar_trip]" class="form-control" accept="image/*" onchange="previewItemImage(this, 'tripPreview0')">
                        <div id="tripPreview0" class="mt-2 d-flex gap-2 flex-wrap"></div>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addItem()">+ Item</button>

        <hr>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
let i = 1;

function addItem() {
    let row = `
    <tr>
        <td><input name="items[${i}][item]" class="form-control" required></td>
        <td><input name="items[${i}][cicilan]" class="form-control" value="0" type="number"></td>
        <td><input name="items[${i}][tagihan]" class="form-control" value="0" type="number"></td>
        <td>
            <input type="file" name="items[${i}][gambar_transfer]" class="form-control" accept="image/*" onchange="previewItemImage(this, 'transferPreview${i}')">
            <div id="transferPreview${i}" class="mt-2 d-flex gap-2 flex-wrap"></div>
        </td>
        <td>
            <input type="file" name="items[${i}][gambar_trip]" class="form-control" accept="image/*" onchange="previewItemImage(this, 'tripPreview${i}')">
            <div id="tripPreview${i}" class="mt-2 d-flex gap-2 flex-wrap"></div>
        </td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✖</button></td>
    </tr>`;
    document.querySelector('#items tbody').insertAdjacentHTML('beforeend', row);
    i++;
}

// Preview per item
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
