@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Detail Pengeluaran Transport</h4>

    {{-- INFO UTAMA --}}
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Tanggal</th>
                    <td>: {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Unit</th>
                    <td>: {{ $pengeluaran->unit->nama_unit ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Total Nominal</th>
                    <td>
                        : <strong>
                            Rp {{ number_format($pengeluaran->total_amount, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- RINCIAN ITEM --}}
    <h5>Rincian Item Pengeluaran</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Keterangan</th>
                <th width="20%">Nominal</th>
                <th width="15%">Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($item->gambar)
                            <a href="{{ asset('storage/'.$item->gambar) }}" target="_blank">
                                <img
                                    src="{{ asset('storage/'.$item->gambar) }}"
                                    width="60"
                                    class="img-thumbnail"
                                >
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Tidak ada item pengeluaran
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-end">Total</th>
                <th colspan="2">
                    {{ number_format(
                        $pengeluaran->items->sum('nominal'),
                        0,
                        ',',
                        '.'
                    ) }}
                </th>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('pengeluaran_transport.rekap') }}" class="btn btn-secondary">
    Kembali
</a>


</div>
@endsection
