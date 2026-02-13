@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h4>Quotation</h4>
        <a href="{{ route('quotations.create') }}" class="btn btn-primary">
            Buat Quotation
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Quote No</th>
                <th>Mitra</th>
                <th>Vessel</th>
                <th>Project</th>
                <th>Date</th>
                <th class="text-end">Total</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotations as $q)

                @php
                    $grandTotal = 0;
                    foreach($q->subItems as $sub){
                        foreach($sub->items as $item){
                            $grandTotal += $item->total;
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $q->quote_no }}</td>
                    <td>{{ $q->mitra->nama_mitra ?? '-' }}</td>
                    <td>{{ $q->vessel->nama_vessel ?? '-' }}</td>
                    <td>{{ $q->project }}</td>
                    <td>{{ $q->date ? \Carbon\Carbon::parse($q->date)->format('d-m-Y') : '-' }}</td>
                    <td class="text-end">
                        Rp {{ number_format($grandTotal,0,',','.') }}
                    </td>
                    <td class="d-flex gap-1">

                        <a href="{{ route('quotations.show',$q->id) }}"
                           class="btn btn-sm btn-info" style="margin-right: 4px;">
                            Detail
                        </a>

                        <a href="{{ route('quotations.edit',$q->id) }}"
                           class="btn btn-sm btn-warning" style="margin-right: 4px;">
                            Edit
                        </a>

                        <form action="{{ route('quotations.destroy',$q->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus quotation ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
