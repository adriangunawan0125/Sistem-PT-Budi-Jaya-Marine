@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-3">Delivery Order</h3>

    <div class="mb-3">
        <strong>PO Klien:</strong> {{ $poMasuk->no_po_klien }} <br>
        <strong>Perusahaan:</strong> {{ $poMasuk->nama_perusahaan }}
    </div>

    <a href="{{ route('delivery-order.create', $poMasuk->id) }}"
       class="btn btn-success mb-3">
        + Buat Delivery Order
    </a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No DO</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deliveryOrders as $do)
                <tr>
                    <td>{{ $do->no_do }}</td>
                    <td>{{ \Carbon\Carbon::parse($do->tanggal_do)->format('d-m-Y') }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ strtoupper($do->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('delivery-order.show', $do->id) }}"
                           class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Belum ada Delivery Order
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
