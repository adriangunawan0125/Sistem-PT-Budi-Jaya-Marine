@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Rekap Pemasukan Transport</h4>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form filter bulan --}}
    <form method="GET" class="mb-3 d-flex align-items-center gap-2 flex-wrap">
        <input type="month"
               name="bulan"
               value="{{ request('bulan') }}"
               class="form-control w-auto">

        <button type="submit" class="btn btn-primary">
            Filter
        </button>

        <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
            Reset
        </a>

        <a href="{{ route('pemasukan.create') }}" class="btn btn-success">
            Tambah Pemasukan
        </a>

        <a href="{{ route('pemasukan.laporan.harian') }}" class="btn btn-info">
            Laporan Harian
        </a>
    </form>

    {{-- Tabel pemasukan --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="50">No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th width="120">Gambar</th>
                    <th>Nominal</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td class="text-center">
                            @if($item->gambar)
                                <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                                     width="80"
                                     class="img-thumbnail"
                                     alt="Bukti {{ $item->deskripsi }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('pemasukan.edit', $item->id) }}"
                               class="btn btn-warning btn-sm mb-1">
                                Edit
                            </a>

                            <form action="{{ route('pemasukan.destroy', $item->id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data pemasukan tidak ditemukan</td>
                    </tr>
                @endforelse

                {{-- Total nominal --}}
                <tr class="table-secondary">
                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
