@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">PO Supplier</h4>
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
                        <th>No PO Internal</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>PO Client</th>
                        <th>Total Beli</th>
                        <th>Discount</th>
                        <th>Grand Total</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($poSuppliers as $po)

                        @php
                            switch($po->status){
                                case 'approved':
                                    $badge = 'bg-success';
                                break;

                                case 'cancelled':
                                    $badge = 'bg-danger';
                                break;

                                default:
                                    $badge = 'bg-secondary';
                                break;
                            }
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $po->no_po_internal }}</strong>
                            </td>

                            <td>{{ $po->nama_perusahaan }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($po->tanggal_po)->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ $po->poMasuk->no_po_klien ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($po->total_beli,0,',','.') }}
                            </td>

                            <td>
                                @if($po->discount_type)
                                    @if($po->discount_type == 'percent')
                                        {{ $po->discount_value }} %
                                    @else
                                        Rp {{ number_format($po->discount_value,0,',','.') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <strong>
                                    Rp {{ number_format($po->grand_total,0,',','.') }}
                                </strong>
                            </td>

                            <td>
                                <span class="badge {{ $badge }}">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>

                            <td>

                                <a href="{{ route('po-supplier.show',$po->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                <a href="{{ route('po-supplier.edit',$po->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('po-supplier.destroy',$po->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus PO Supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>

                                <a href="{{ route('po-masuk.show', $po->po_masuk_id) }}"
                                   class="btn btn-sm btn-secondary">
                                    PO Client
                                </a>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center p-4">
                                Belum ada PO Supplier
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
