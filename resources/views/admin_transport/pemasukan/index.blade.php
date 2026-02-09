@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-5">Rekap Pemasukan Transport (Harian)</h4>

    {{-- FILTER --}}
    <form method="GET" class="mb-3">

        <div class="d-flex flex-wrap align-items-end" style="margin-right: 7px;">

            {{-- TANGGAL --}}
            <div class="me-3 mb-2" style="margin-right: 7px;">
                <label class="form-label mb-1">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ request('tanggal', date('Y-m-d')) }}"
                       class="form-control"
                       style="width:170px">
            </div>

            {{-- MITRA --}}
            <div class="me-3 mb-2" style="margin-right: 7px;">
                <label class="form-label mb-1">Mitra</label>
                <input type="text"
                       name="nama"
                       value="{{ request('nama') }}"
                       placeholder="Cari nama mitra"
                       class="form-control"
                       style="width:220px">
            </div>

            {{-- KATEGORI --}}
            <select name="kategori"
                class="form-control d-inline-block me-2 mb-2"
                style="width:160px; margin-right:20px;">
            <option value="">Semua Kategori</option>
            <option value="setoran" {{ request('kategori')=='setoran'?'selected':'' }}>Setoran</option>
            <option value="cicilan" {{ request('kategori')=='cicilan'?'selected':'' }}>Cicilan</option>
            <option value="deposit" {{ request('kategori')=='deposit'?'selected':'' }}>Deposit</option>
        </select>

            {{-- CHECKBOX (INI YANG DIPENTING) --}}
            <div class="form-check me-3 mb-2" style="margin-right: 40px;">
                <input class="form-check-input"
                       type="checkbox"
                       name="tidak_setor"
                       value="1"
                       {{ request('tidak_setor')?'checked':'' }}>
                <label class="form-check-label">Tidak TF</label>
            </div>

            {{-- BUTTON GROUP --}}
            <div class="mb-2" style="margin-left:10px">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">Reset</a>
                <a href="{{ route('pemasukan.create') }}" class="btn btn-success">Tambah</a>
                <a href="{{ route('pemasukan.laporan.harian', ['tanggal'=>request('tanggal')]) }}"
                   class="btn btn-info text-white">Laporan Harian</a>
            </div>

        </div>

    </form>


    {{-- CARD MITRA TIDAK SETOR (OTOMATIS MUNCUL) --}}
    @if(isset($mitraKosong))
    <div class="card border-danger mb-3">
        <div class="card-header bg-danger text-white">
            Mitra Tidak TF ({{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }})
        </div>
        <div class="card-body">
            @forelse($mitraKosong as $m)
                <span class="badge bg-secondary text-light me-1 mb-1">
                    {{ $m->nama_mitra }}
                </span>
            @empty
                <span class="text-muted">Semua mitra sudah setor</span>
            @endforelse
        </div>
    </div>
    @endif


    {{-- TABLE --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Mitra</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Nominal</th>
                <th width="130">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pemasukan as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->mitra->nama_mitra ?? '-' }}</td>
                <td class="text-center">{{ ucfirst($item->kategori) }}</td>
                <td>{{ $item->deskripsi }}</td>

                <td class="text-center">
                    @if($item->gambar)
                        <img src="{{ asset('storage/pemasukan/'.$item->gambar) }}"
                             width="65"
                             class="img-thumbnail">
                    @else
                        -
                    @endif
                </td>

                <td><b>Rp {{ number_format($item->nominal,0,',','.') }}</b></td>

                <td class="text-center">
                    <a href="{{ route('pemasukan.edit',$item->id) }}"
                       class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('pemasukan.destroy',$item->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Hapus data?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse

            <tr>
                <th colspan="6" class="text-end">TOTAL</th>
                <th colspan="2">Rp {{ number_format($total,0,',','.') }}</th>
            </tr>

        </tbody>
    </table>

</div>
@endsection
