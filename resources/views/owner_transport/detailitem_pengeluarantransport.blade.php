@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Detail Item Pengeluaran</h4>

    {{-- INFO UTAMA --}}
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Tanggal</th>
                    <td>: {{ \Carbon\Carbon::parse($item->pengeluaran->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Unit</th>
                    <td>: {{ $item->pengeluaran->unit->nama_unit ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>: {{ $item->keterangan }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td>
                        : <strong>
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- BUKTI --}}
    <div class="card">
        <div class="card-header">
            Bukti Pengeluaran
        </div>
        <div class="card-body text-center">
            @if($item->gambar)
                <img src="{{ asset('storage/'.$item->gambar) }}"
                     class="img-fluid rounded"
                     style="max-height:500px">
            @else
                <span class="text-muted">Tidak ada bukti gambar</span>
            @endif
        </div>
    </div>

    <a href="{{ route('pengeluaran_transport.show', $item->transport_id) }}"
       class="btn btn-secondary mt-3">
        Kembali
    </a>

</div>
@endsection
