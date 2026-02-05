@extends('layouts.app')

@section('content')
<div class="container">
    <h4>PO Supplier</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>No PO Supplier</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>PO Masuk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($poSuppliers as $po)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $po->no_po_supplier }}</td>
                <td>{{ $po->nama_supplier }}</td>
                <td>{{ $po->tanggal_po }}</td>
                <td>
                    {{ $po->poMasuk->no_po_klien ?? '-' }}
                </td>
                <td>
                    <span class="badge bg-success">
                        {{ $po->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('po-masuk.show', $po->po_masuk_id) }}"
                       class="btn btn-sm btn-info">
                        Lihat PO Masuk
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">
                    Belum ada PO Supplier
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
