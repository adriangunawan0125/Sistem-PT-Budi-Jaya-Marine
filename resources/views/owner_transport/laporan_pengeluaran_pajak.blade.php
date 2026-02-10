@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-5">
        Laporan Pengeluaran Pajak Mobil - 
        {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
    </h4>

    {{-- FILTER --}}
    <form method="GET" class="mb-3" id="filterForm">

        <div class="d-flex flex-wrap align-items-end">

            {{-- BULAN --}}
            <div class="me-3 mb-2" style="margin-right: 10px;">
                <label class="form-label mb-1">Bulan</label>
                <input type="month"
                       name="bulan"
                       value="{{ $bulan }}"
                       class="form-control"
                       style="width:170px">
            </div>

            {{-- BUTTON --}}
            <div class="mb-2">
                <button class="btn btn-primary">Tampilkan</button>

                <a href="{{ route('pengeluaran_pajak.rekap') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </form>

    {{-- INFO TOTAL --}}
    <div class="alert alert-info mb-4">
        Total Pengeluaran Pajak:
        <strong>Rp {{ number_format($total_all, 0, ',', '.') }}</strong>
    </div>

    {{-- TABLE --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>No</th>
                <th>Unit</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pengeluaran as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->unit->nama_unit ?? '-' }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                    </td>
                    <td>{{ $item->deskripsi }}</td>
                    <td><b>Rp {{ number_format($item->nominal,0,',','.') }}</b></td>
                    <td class="text-center">
                        <a href="{{ route('pengeluaran_pajak.detail', $item->id) }}"
                           class="btn btn-sm btn-info btn-loading">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Tidak ada data pengeluaran pajak
                    </td>
                </tr>
            @endforelse

            @if($pengeluaran->count())
            <tr>
                <th colspan="4" class="text-end">TOTAL</th>
                <th>Rp {{ number_format($total_all,0,',','.') }}</th>
                <th></th>
            </tr>
            @endif
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
