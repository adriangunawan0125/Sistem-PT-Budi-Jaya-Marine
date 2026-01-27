@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Laporan Invoice</h4>

<form method="GET" class="mb-3">
    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">

        {{-- Cari Nama --}}
        <input
            type="text"
            name="nama"
            value="{{ $nama }}"
            placeholder="Cari nama mitra"
            class="form-control"
            style="width: 240px;"
        >

        {{-- Status --}}
        <select
            name="status"
            class="form-control"
            style="width: 170px;"
        >
            <option value="">-- Semua Status --</option>
            <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum_lunas" {{ $status == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
        </select>

        {{-- Cari --}}
        <button type="submit" class="btn btn-primary px-3">
            Tampilkan
        </button>

        {{-- Reset --}}
        <a href="{{ route('invoice.rekap') }}" class="btn btn-secondary px-3">
            Reset
        </a>

    </div>
</form>



    {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-4">
        Total Invoice: <strong>Rp {{ number_format($total_all, 0, ',', '.') }}</strong>
    </div>

    {{-- TABEL --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Mitra</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
               @forelse($invoices as $index => $inv)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $inv->mitra->nama_mitra ?? '-' }}</td>
    <td>{{ \Carbon\Carbon::parse($inv->tanggal)->format('d-m-Y') }}</td>
    <td>{{ ucfirst($inv->status) }}</td>
    <td>Rp {{ number_format($inv->total,0,',','.') }}</td>
    <td>
       <a class="btn btn-info" href="{{ route('owner.invoice.show', $inv->id) }}">Detail</a>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-muted">Tidak ada invoice</td>
</tr>
@endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection
