@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Detail Pemasukan</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-borderless mb-0">
                <tr>
                    <td width="200"><b>Tanggal</b></td>
                    <td>
                        {{ \Carbon\Carbon::parse($pemasukan->tanggal)->format('d-m-Y') }}
                    </td>
                </tr>

                <tr>
                    <td><b>Mitra</b></td>
                    <td>{{ $pemasukan->mitra->nama_mitra ?? '-' }}</td>
                </tr>

                <tr>
                    <td><b>Kategori</b></td>
                    <td>{{ ucfirst($pemasukan->kategori) }}</td>
                </tr>

                <tr>
                    <td><b>Deskripsi</b></td>
                    <td>{{ $pemasukan->deskripsi ?? '-' }}</td>
                </tr>

                <tr>
                    <td><b>Nominal</b></td>
                    <td class="fw-bold text-success">
                        Rp {{ number_format($pemasukan->nominal,0,',','.') }}
                    </td>
                </tr>

                <tr>
                    <td><b>Bukti Gambar</b></td>
                    <td>
                        @if($pemasukan->gambar)
                            <img 
                                src="{{ asset('storage/pemasukan/'.$pemasukan->gambar) }}" 
                                alt="Bukti Pemasukan"
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
