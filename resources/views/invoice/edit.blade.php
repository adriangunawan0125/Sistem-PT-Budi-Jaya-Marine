@extends('layouts.app')

@section('content')
<div class="container">
<h4>Edit Invoice</h4>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
@csrf
@method('PUT')

{{-- ===================== --}}
{{-- MITRA --}}
{{-- ===================== --}}
<div class="mb-3">
    <label>Mitra</label>
    <select name="mitra_id" class="form-control" required>
        <option value="">-- Pilih Mitra --</option>
        @foreach($mitras as $m)
            <option value="{{ $m->id }}"
                {{ $invoice->mitra_id == $m->id ? 'selected' : '' }}>
                {{ $m->nama_mitra }}
            </option>
        @endforeach
    </select>
</div>

{{-- ===================== --}}
{{-- TANGGAL --}}
{{-- ===================== --}}
<div class="mb-3">
    <label>Tanggal</label>
    <input type="date"
           name="tanggal"
           class="form-control"
           value="{{ $invoice->tanggal }}"
           required>
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

@foreach($invoice->items as $i => $item)
<tr>
    <td>
        <input name="items[{{ $i }}][item]"
               class="form-control"
               value="{{ $item->item }}"
               required>
    </td>
    <td>
        <input name="items[{{ $i }}][cicilan]"
               class="form-control"
               type="number"
               value="{{ $item->cicilan }}">
    </td>
    <td>
        <input name="items[{{ $i }}][tagihan]"
               class="form-control"
               type="number"
               value="{{ $item->tagihan }}">
    </td>
    <td>
        <button type="button"
                class="btn btn-danger btn-sm"
                onclick="this.closest('tr').remove()">✖</button>
    </td>
</tr>
@endforeach

</tbody>
</table>

<button type="button"
        class="btn btn-sm btn-secondary"
        onclick="addItem()">+ Item</button>

<hr>

<div class="d-flex justify-content-between">
    <a href="{{ route('invoice.show', $invoice->mitra_id) }}"
       class="btn btn-secondary">
        Kembali
    </a>

    <button class="btn btn-primary">
        Simpan Perubahan
    </button>
</div>

</form>
</div>

{{-- ===================== --}}
{{-- SCRIPT --}}
{{-- ===================== --}}
<script>
let i = {{ $invoice->items->count() }};

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
