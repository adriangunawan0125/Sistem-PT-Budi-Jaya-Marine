@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Laporan Pengeluaran Transport - Bulan {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</h4>

    {{-- FILTER BULAN --}}
    <form method="GET" class="mb-3" id="filterForm">
        <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto d-inline">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('pengeluaran_transport.rekap') }}" class="btn btn-secondary">Kembali</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Unit</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $total_all = 0; @endphp
            @foreach($pengeluaran as $index => $t)
                @php
                    $unit_total = $t->items->sum('nominal');
                    $total_all += $unit_total;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->unit->nama_unit ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($unit_total,0,',','.') }}</td>
                    <td>
                        <a href="{{ route('pengeluaran_transport.show', $t->id) }}" class="btn btn-sm btn-info btn-loading">Detail</a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total Keseluruhan</strong></td>
                <td colspan="2"><strong>Rp {{ number_format($total_all,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

</div>
{{-- MODAL LOADING --}}
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <h5 class="mb-0">Memuat data...</h5>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // FILTER submit
    document.getElementById('filterForm').addEventListener('submit', function () {
        loadingModal.show();
    });

    // SEMUA LINK PINDAH HALAMAN
    document.querySelectorAll('.btn-loading').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            loadingModal.show();

            let url = this.getAttribute('href');

            setTimeout(function(){
                window.location.href = url;
            }, 350);
        });
    });

});
</script>

@endsection
