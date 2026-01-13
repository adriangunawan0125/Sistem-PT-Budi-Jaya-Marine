@extends('layouts.app')

@section('content')
<div class="container">
<h4>Buat Invoice</h4>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- ===================== --}}
{{-- MITRA --}}
{{-- ===================== --}}

@if(isset($mitra))
    {{-- MODE OTOMATIS (DARI DETAIL INVOICE) --}}
    <input type="hidden" name="mitra_id" value="{{ $mitra->id }}">

    <div class="mb-3">
        <label>Mitra</label>
        <input type="text"
               class="form-control"
               value="{{ $mitra->nama_mitra }}"
               readonly>
    </div>
@else
    {{-- MODE MANUAL (MENU INVOICE) --}}
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

{{-- ===================== --}}
{{-- TANGGAL --}}
{{-- ===================== --}}
<div class="mb-3">
    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control" required>
</div>

<hr>

{{-- ===================== --}}
{{-- ITEM INVOICE --}}
{{-- ===================== --}}
<h5>Item Invoice</h5>

<table class="table" id="items">
<thead>
<tr>
    <th>Item</th>
    <th>Cicilan</th>
    <th>Tagihan</th>
    <th width="60"></th>
</tr>
</thead>
<tbody>
<tr>
    <td>
        <input name="items[0][item]" class="form-control" required>
    </td>
    <td>
        <input name="items[0][cicilan]" class="form-control" value="0" type="number">
    </td>
    <td>
        <input name="items[0][tagihan]" class="form-control" value="0" type="number">
    </td>
    <td></td>
</tr>
</tbody>
</table>

<button type="button" class="btn btn-sm btn-secondary" onclick="addItem()">+ Item</button>

<hr>

{{-- ===================== --}}
{{-- UPLOAD --}}
{{-- ===================== --}}
<div class="mb-3">
    <label>Bukti Transfer</label>
    <input type="file" name="transfer[]" class="form-control" multiple>
</div>

<div class="mb-3">
    <label>Bukti Perjalanan</label>
    <input type="file" name="trip[]" class="form-control" multiple>
</div>

<button class="btn btn-primary">Simpan</button>
</form>
</div>

{{-- ===================== --}}
{{-- SCRIPT --}}
{{-- ===================== --}}
<script>
let i = 1;
function addItem() {
    let row = `
    <tr>
        <td>
            <input name="items[${i}][item]" class="form-control" required>
        </td>
        <td>
            <input name="items[${i}][cicilan]" class="form-control" value="0" type="number">
        </td>
        <td>
            <input name="items[${i}][tagihan]" class="form-control" value="0" type="number">
        </td>
        <td>
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove()">✖</button>
        </td>
    </tr>`;
    document.querySelector('#items tbody').insertAdjacentHTML('beforeend', row);
    i++;
}
</script>
@endsection
