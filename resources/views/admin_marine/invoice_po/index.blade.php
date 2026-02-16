@extends('layouts.app')

@section('content')
<div class="container">

<h4 class="mb-4">Invoice PO</h4>

<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-bordered mb-0">
<thead class="table-dark">
<tr>
<th width="5%">No</th>
<th>No Invoice</th>
<th>Vessel</th>
<th>PO</th>
<th>Tanggal</th>
<th>Periode</th>
<th width="150">Grand Total</th>
<th width="140">Aksi</th>
</tr>
</thead>
<tbody>

@forelse($invoices as $index => $invoice)
<tr>
<td>{{ $index+1 }}</td>
<td>{{ $invoice->no_invoice }}</td>
<td>{{ $invoice->poMasuk->vessel ?? '-' }}</td>
<td>{{ $invoice->poMasuk->no_po_klien ?? '-' }}</td>
<td>{{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d M Y') }}</td>
<td>{{ $invoice->periode }}</td>
<td>Rp {{ number_format($invoice->grand_total,0,',','.') }}</td>
<td>
<a href="{{ route('invoice-po.show',$invoice->id) }}"
   class="btn btn-sm btn-info">
Detail
</a>
</td>
</tr>
@empty
<tr>
<td colspan="8" class="text-center text-muted">
Belum ada Invoice
</td>
</tr>
@endforelse

</tbody>
</table>

</div>
</div>

</div>
@endsection
