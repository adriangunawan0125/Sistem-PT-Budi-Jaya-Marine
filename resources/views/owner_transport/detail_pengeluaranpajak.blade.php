@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Detail Pengeluaran Pajak Mobil</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-borderless mb-0">
                <tr>
                    <td width="200"><b>Unit</b></td>
                    <td>{{ $pengeluaran->unit->nama_unit ?? '-' }}</td>
                </tr>

                <tr>
                    <td><b>Tanggal</b></td>
                    <td>
                        {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d-m-Y') }}
                    </td>
                </tr>

                <tr>
                    <td><b>Deskripsi</b></td>
                    <td>{{ $pengeluaran->deskripsi ?? '-' }}</td>
                </tr>

                <tr>
                    <td><b>Nominal</b></td>
                    <td class="fw-bold text-danger">
                        Rp {{ number_format($pengeluaran->nominal,0,',','.') }}
                    </td>
                </tr>

                <tr>
                    <td><b>Bukti Gambar</b></td>
                    <td>
                        @if($pengeluaran->gambar)
                            <img 
                                src="{{ asset('storage/'.$pengeluaran->gambar) }}" 
                                alt="Bukti Pengeluaran Pajak"
                                class="img-thumbnail"
                                style="max-width:300px"
                            >
                        @else
                            <span class="text-muted">Tidak ada gambar</span>
                        @endif
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
        Kembali
    </a>

</div>
@endsection
