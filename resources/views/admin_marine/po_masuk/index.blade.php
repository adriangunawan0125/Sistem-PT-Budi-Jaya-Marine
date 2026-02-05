@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Purchase Order (PO)</h2>

        <a href="{{ route('po-masuk.create') }}" class="btn btn-success">
            + Tambah 
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No PO</th>
                <th>Perusahaan</th>
                <th>Vessel</th>
                <th>Total Jual</th>
                <th>Total Beli</th>
                <th>Margin</th>
                <th>Status</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($poMasuk as $po)
                <tr>
                    <td>{{ $po->no_po_klien }}</td>
                    <td>{{ $po->nama_perusahaan }}</td>
                    <td>{{ $po->vessel }}</td>
                    <td>Rp {{ number_format($po->total_jual ?? 0) }}</td>
                    <td>Rp {{ number_format($po->totalBeli() ?? 0) }}</td>
                    <td>
                        Rp {{ number_format($po->hitungMargin() ?? 0) }}
                    </td>
                    <td>
                        <span class="badge bg-info text-light">
                            {{ strtoupper($po->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('po-masuk.show', $po->id) }}"
                           class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Belum ada PO Masuk
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
