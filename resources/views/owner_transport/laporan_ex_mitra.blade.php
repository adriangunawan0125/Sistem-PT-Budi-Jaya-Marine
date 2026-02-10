@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Laporan Ex Mitra</h4>

    {{-- SEARCH --}}
    <form method="GET" class="mb-3" id="filterForm">
        <div class="input-group" style="max-width:400px">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control"
                   placeholder="Cari nama / no hp / alamat">
            <button style="margin-left:4px;" class="btn btn-primary">Cari</button>
        </div>
    </form>

    <div class="alert alert-info">
        Total Ex Mitra: <strong>{{ $total }}</strong>
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Mitra</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Kontrak Mulai</th>
                    <th>Kontrak Berakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mitras as $no => $mitra)
                <tr>
                    <td>{{ $mitras->firstItem() + $no }}</td>
                    <td>{{ $mitra->nama_mitra }}</td>
                    <td>{{ $mitra->alamat }}</td>
                    <td>{{ $mitra->no_hp }}</td>
                    <td>{{ $mitra->kontrak_mulai?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $mitra->kontrak_berakhir?->format('d-m-Y') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('mitra.detail', $mitra->id) }}" class="btn btn-sm btn-info btn-loading">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada ex mitra</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mb-3">
        {{ $mitras->links() }}
    </div>

    <a href="{{ route('mitra.aktif') }}" class="btn btn-secondary">Kembali</a>
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
