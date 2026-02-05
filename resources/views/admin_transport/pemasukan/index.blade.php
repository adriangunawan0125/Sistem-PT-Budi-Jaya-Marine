@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h4 class="mb-4 text-secondary fw-semibold">
        Rekap Pemasukan Transport (Harian)
    </h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= FILTER ================= --}}
    <form method="GET" class="mb-4">

        {{-- Tanggal --}}
        <input type="date"
               name="tanggal"
               value="{{ request('tanggal', date('Y-m-d')) }}"
               class="form-control d-inline-block me-2 mb-2"
               style="width:170px">

        {{-- Nama Mitra --}}
        <input type="text"
               name="nama"
               value="{{ request('nama') }}"
               placeholder="Cari nama mitra"
               class="form-control d-inline-block me-2 mb-2"
               style="width:200px">

        {{-- Kategori --}}
        <select name="kategori"
                class="form-control d-inline-block me-2 mb-2"
                style="width:160px;">
            <option value="">Semua Kategori</option>
            <option value="setoran" {{ request('kategori')=='setoran'?'selected':'' }}>Setoran</option>
            <option value="cicilan" {{ request('kategori')=='cicilan'?'selected':'' }}>Cicilan</option>
            <option value="deposit" {{ request('kategori')=='deposit'?'selected':'' }}>Deposit</option>
        </select>

        {{-- Tidak setor --}}
        <div class="form-check d-inline-block " style="margin-left:10px; margin-right:20px;">
            <input class="form-check-input"
                   type="checkbox"
                   name="tidak_setor"
                   value="1"
                   {{ request('tidak_setor')?'checked':'' }}>
            <label class="form-check-label">
               Hari ini Tidak TF 
            </label>
        </div>

        <button type="submit" class="btn btn-primary me-2 mb-2">Filter</button>
        <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary me-2 mb-2">Reset</a>

        <a href="{{ route('pemasukan.create') }}" class="btn btn-success me-2 mb-2">
            Tambah Pemasukan
        </a>

        <a href="{{ route('pemasukan.laporan.harian') }}" class="btn btn-info text-white me-2 mb-2">
            Laporan Harian
        </a>
    </form>


    {{-- ================= MITRA TIDAK SETOR ================= --}}
    @if(isset($mitraKosong))
    <div class="card border-danger mb-4">
        <div class="card-header bg-danger text-white">
            Mitra Tidak TF ({{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }})
        </div>
        <div class="card-body">
            @forelse($mitraKosong as $m)
                <span class="badge bg-secondary text-light me-1 mb-1">
                    {{ $m->nama_mitra }}
                </span>
            @empty
                <p class="text-muted mb-0">Semua mitra sudah setor</p>
            @endforelse
        </div>
    </div>
    @endif


    {{-- ================= TABLE ================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Tanggal</th>
                            <th>Mitra</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center" width="120">Gambar</th>
                            <th>Nominal</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pemasukan ?? [] as $item)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $item->mitra->nama_mitra ?? '-' }}</td>
                            <td>{{ ucfirst($item->kategori) }}</td>

                            <td class="text-muted">{{ $item->deskripsi }}</td>
                            <td class="text-center">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                                         width="70"
                                         class="rounded">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="fw-semibold">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td>
                                <a href="{{ route('pemasukan.edit', $item->id) }}"
                                   class="btn btn-warning btn-sm me-1 mb-1">
                                    Edit
                                </a>

                                <form action="{{ route('pemasukan.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus?')"
                                            class="btn btn-danger btn-sm me-1 mb-1">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Data pemasukan tidak ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end fw-semibold pe-3">
                                Total
                            </td>
                            <td colspan="2" class="fw-bold">
                                Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection
