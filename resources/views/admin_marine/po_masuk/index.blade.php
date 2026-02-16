@extends('layouts.app')

@section('content')
<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">
<h4>PO Masuk (Client PO)</h4>

<a href="{{ route('po-masuk.create') }}"
class="btn btn-primary">
+ Create PO
</a>
</div>

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="table-dark">
<tr>
<th width="5%">No</th>
<th>No PO Klien</th>
<th>Mitra</th>
<th>Vessel</th>
<th>Tanggal</th>
<th>Total Jual</th>
<th>Total Beli</th>
<th>Pengeluaran</th>
<th>Margin</th>
<th>Status</th>
<th width="18%">Action</th>
</tr>
</thead>

<tbody>

@forelse($poMasuk as $index => $po)

@php
    $totalBeli = $po->poSuppliers->sum('grand_total');
    $totalPengeluaran = $po->pengeluaran->sum('amount');
    $margin = $po->total_jual - $totalBeli - $totalPengeluaran;

    if($margin > 0){
        $marginClass = 'text-success';
        $marginLabel = 'Profit';
    } elseif($margin < 0){
        $marginClass = 'text-danger';
        $marginLabel = 'Loss';
    } else {
        $marginClass = 'text-warning';
        $marginLabel = 'Break Even';
    }
@endphp

<tr>
<td>{{ $index + 1 }}</td>

<td><strong>{{ $po->no_po_klien }}</strong></td>

<td>{{ $po->mitra_marine }}</td>

<td>{{ $po->vessel }}</td>

<td>{{ \Carbon\Carbon::parse($po->tanggal_po)->format('d-m-Y') }}</td>

<td>Rp {{ number_format($po->total_jual,0,',','.') }}</td>

<td>Rp {{ number_format($totalBeli,0,',','.') }}</td>

<td>Rp {{ number_format($totalPengeluaran,0,',','.') }}</td>

<td class="{{ $marginClass }}">
<strong>
Rp {{ number_format($margin,0,',','.') }}
</strong>
<br>
<small>{{ $marginLabel }}</small>
</td>

<td>
<span class="badge text-light
@if($po->status == 'draft') bg-secondary
@elseif($po->status == 'approved') bg-primary
@elseif($po->status == 'processing') bg-warning text-dark
@elseif($po->status == 'delivered') bg-info
@elseif($po->status == 'closed') bg-success
@endif">
{{ strtoupper($po->status) }}
</span>
</td>

<td>

<a href="{{ route('po-masuk.show',$po->id) }}"
class="btn btn-sm btn-info">
Detail
</a>

<form action="{{ route('po-masuk.destroy',$po->id) }}"
method="POST"
class="d-inline">
@csrf
@method('DELETE')

<button type="submit"
class="btn btn-sm btn-danger"
onclick="return confirm('Yakin ingin hapus PO ini?')">
Delete
</button>
</form>

</td>

</tr>

@empty
<tr>
<td colspan="11" class="text-center p-4">
Belum ada data PO Masuk
</td>
</tr>
@endforelse

</tbody>
</table>

</div>
</div>

</div>
@endsection
