@extends('layouts.app')

@section('content')

<div class="container-fluid">

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body py-3">
        <form method="GET" class="row align-items-end g-3" id="filterForm">

            <div class="col-md-4">
                <label class="form-label fw-semibold">Bulan</label>
                <select name="bulan" class="form-control">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (int)$bulan === $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun</label>
                <select name="tahun" class="form-control">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary px-4">Filter</button>
                <div style="width:12px"></div>
                <a href="{{ route('admin.marine.dashboard') }}"
                   class="btn btn-secondary px-4">
                    Reset
                </a>
            </div>

        </form>
    </div>
</div>

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Dashboard Admin Marine</h4>
    <span class="text-light badge bg-primary px-3 py-2">
        {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
    </span>
</div>

{{-- ================= STAT CARDS ================= --}}
@php
    $marginBulanan = $totalPemasukanBulanan - $totalPengeluaranBulanan;
@endphp

<div class="row g-4 mb-4">

    {{-- PO --}}
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card bg-primary text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-semibold">PO Bulan Ini</div>
                    <div class="h4 fw-bold mt-2">{{ $jumlahPo }}</div>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- PEMASUKAN --}}
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card bg-success text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-semibold">Pemasukan</div>
                    <div class="h5 fw-bold mt-2">
                        Rp {{ number_format($totalPemasukanBulanan,0,',','.') }}
                    </div>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- PENGELUARAN --}}
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card bg-danger text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-semibold">Pengeluaran</div>
                    <div class="h5 fw-bold mt-2">
                        Rp {{ number_format($totalPengeluaranBulanan,0,',','.') }}
                    </div>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- MARGIN --}}
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-card {{ $marginBulanan >= 0 ? 'bg-info' : 'bg-dark' }} text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small fw-semibold">Margin</div>
                    <div class="h5 fw-bold mt-2">
                        Rp {{ number_format(abs($marginBulanan),0,',','.') }}
                    </div>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

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
<style>
.dashboard-card {
    border-radius: 14px;
    min-height: 110px;
}

.dashboard-card .card-body {
    padding: 20px;
}

.icon-circle {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
    font-size: 20px;
}
</style>
{{-- ================= ROW 2 ================= --}}
<div class="row g-4 mb-4">

    {{-- KOMPOSISI --}}
    <div class="col-lg-5">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-white fw-semibold">
                Komposisi Project Bulanan
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div style="width:260px;height:260px;">
                    <canvas id="chartPieBulanan"></canvas>
                </div>
            </div>
        </div>
    </div>

  {{-- RECENT INCOME CLEAN VERSION --}}
<div class="col-lg-7">
    <div class="card shadow-sm border-0 h-100 recent-income-card">
        <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-coins text-success me-2"></i>
                Recent Income
            </span>
            <small class="text-muted">5 Transaksi Terakhir</small>
        </div>

        <div class="card-body p-0">

            @forelse($recentIncome as $income)
            <div class="income-item d-flex justify-content-between align-items-center">

                <div>
                    <div class="fw-semibold">
                        {{ $income->nama_pengirim }}
                    </div>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($income->tanggal)->format('d M Y') }}
                    </small>
                </div>

                <div class="text-end">
                    <div class="fw-bold text-success">
                        Rp {{ number_format($income->nominal,0,',','.') }}
                    </div>
                </div>

            </div>
            @empty
            <div class="text-center text-muted py-4">
                Tidak ada data
            </div>
            @endforelse

        </div>
    </div>
</div>
<style>
.recent-income-card {
    border-radius: 14px;
}

.recent-income-card .card-header {
    border-bottom: 1px solid #f1f1f1;
    font-size: 14px;
}

.income-item {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f3f3;
    transition: background 0.2s ease;
}

.income-item:last-child {
    border-bottom: none;
}

.income-item:hover {
    background-color: #f9f9f9;
}

.income-item .fw-bold {
    font-size: 15px;
}
</style>
</div>
{{-- ================= ROW 3 (2 CHART ATAS) ================= --}}
<div class="row g-4 mb-4">

    {{-- PEMASUKAN TAHUNAN --}}
    <div class="col-lg-6">
        <div class="card shadow border-0 chart-card">
            <div class="card-header bg-white fw-semibold">
                Pemasukan Tahunan
            </div>
            <div class="card-body">
                <canvas id="chartPemasukanTahunan"></canvas>
            </div>
        </div>
    </div>

    {{-- PENGELUARAN TAHUNAN --}}
    <div class="col-lg-6">
        <div class="card shadow border-0 chart-card">
            <div class="card-header bg-white fw-semibold">
                Pengeluaran Tahunan
            </div>
            <div class="card-body">
                <canvas id="chartPengeluaranTahunan"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ================= ROW 4 (FULL WIDTH PERBANDINGAN) ================= --}}
<div class="row">

    <div class="col-12">
        <div class="card shadow border-0 chart-card-large">
            <div class="card-header bg-white fw-semibold">
                Perbandingan Tahunan
            </div>
            <div class="card-body">
                <canvas id="chartPerbandinganTahunan"></canvas>
            </div>
        </div>
    </div>

</div>
</div>

<style>
.stat-card { border-radius:12px; }

.chart-card {
    height:360px;
}

.chart-card .card-body {
    height:300px;
}

.chart-card-large {
    height:420px;
}

.chart-card-large .card-body {
    height:350px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

function formatRupiah(v){
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
}

function chartOption(){
    return {
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            tooltip:{callbacks:{label:(ctx)=>formatRupiah(ctx.raw)}}
        },
        scales:{
            y:{ticks:{callback:(v)=>formatRupiah(v)}}
        }
    }
}

/* PIE */
new Chart(document.getElementById('chartPieBulanan'),{
    type:'doughnut',
    data:{
        labels:['Pengeluaran PO','Nilai PO Masuk','Nilai PO Kita'],
        datasets:[{
            data:[
              
                {{ $totalPengeluaranBulanan }},
                {{ $totalNilaiPoMasukBulanan }},
                {{ $totalNilaiPoSupplierBulanan }}
            ],
            backgroundColor:['#dc3545','#007bff','#ffc107']
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}
});

/* LINE PEMASUKAN */
new Chart(document.getElementById('chartPemasukanTahunan'),{
    type:'line',
    data:{labels:@json($labels),
        datasets:[{label:'Pemasukan',
        data:@json($pemasukanTahunan),
        borderColor:'#28a745',
        tension:.3,
        fill:false}]},
    options:chartOption()
});

/* LINE PENGELUARAN */
new Chart(document.getElementById('chartPengeluaranTahunan'),{
    type:'line',
    data:{labels:@json($labels),
        datasets:[{label:'Pengeluaran',
        data:@json($pengeluaranTahunan),
        borderColor:'#dc3545',
        tension:.3,
        fill:false}]},
    options:chartOption()
});

/* BAR */
new Chart(document.getElementById('chartPerbandinganTahunan'),{
    type:'bar',
    data:{labels:@json($labels),
        datasets:[
            {label:'Pemasukan',data:@json($pemasukanTahunan),backgroundColor:'#28a745'},
            {label:'Pengeluaran',data:@json($pengeluaranTahunan),backgroundColor:'#dc3545'}
        ]},
    options:chartOption()
});


</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // FILTER -> tampilkan loading
    document.getElementById('filterForm').addEventListener('submit', function () {
        loadingModal.show();
    });

    // Semua link internal (opsional kalau mau lebih smooth)
    document.querySelectorAll('a').forEach(function(link){
        if(link.getAttribute('href') && !link.getAttribute('href').startsWith('#')){
            link.addEventListener('click', function(){
                loadingModal.show();
            });
        }
    });

});
</script>

@endsection