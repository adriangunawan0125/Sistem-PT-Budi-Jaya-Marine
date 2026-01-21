@extends('layouts.app')

@section('content')

<div class="container-fluid">

  {{-- FILTER BULAN --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row align-items-end g-3">

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Bulan
                </label>
                <select name="bulan" class="form-control">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (int)$bulan === $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Tahun
                </label>
                <select name="tahun" class="form-control">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

           <div class="col-md-4 d-flex align-items-end">
    <button class="btn btn-primary px-4">
        <i class="fas fa-filter mr-1"></i>
        Filter
    </button>

    <div style="width:12px"></div> {{-- SPACER --}}

    <a href="{{ route('admin.transport.dashboard') }}"
       class="btn btn-outline-secondary px-4">
        Reset
    </a>
</div>



        </form>
    </div>
</div>


    {{-- PAGE HEADING --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            Dashboard Admin Transport
        </h1>
        <span class="badge badge-primary px-3 py-2">
            {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
        </span>
    </div>

    {{-- STAT CARDS --}}
    <div class="row">

        {{-- MITRA BELUM LUNAS --}}
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card dashboard-card bg-gradient-danger">
        <div class="card-body text-white">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-uppercase small font-weight-bold">
                        Invoice Mitra Belum Lunas
                    </div>
                    <div class="h4 font-weight-bold mt-2">
                        {{ $mitraBelumLunas }}
                    </div>
                    <small>
                        Mitra
                    </small>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
        </div>
    </div>
</div>


        {{-- TOTAL MITRA --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-gradient-primary">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-uppercase small font-weight-bold">
                                Total Mitra
                            </div>
                            <div class="h4 font-weight-bold mt-2">
                                {{ $totalMitra }}
                            </div>
                            <small>Partner Aktif</small>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EX MITRA --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-gradient-dark">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-uppercase small font-weight-bold">
                                Ex Mitra
                            </div>
                            <div class="h4 font-weight-bold mt-2">
                                {{ $totalExMitra }}
                            </div>
                            <small>Non Aktif</small>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PENGELUARAN INTERNAL --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-gradient-warning">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-uppercase small font-weight-bold">
                                Pengeluaran Internal
                            </div>
                            <div class="h4 font-weight-bold mt-2">
                                Rp {{ number_format($totalPengeluaranInternal, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAJAK --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-gradient-info">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-uppercase small font-weight-bold">
                                Pengeluaran Pajak Mobil
                            </div>
                            <div class="h4 font-weight-bold mt-2">
                                Rp {{ number_format($totalPengeluaranPajak, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TRANSPORT --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-gradient-success">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-uppercase small font-weight-bold">
                                Pengeluaran Transport
                            </div>
                            <div class="h4 font-weight-bold mt-2">
                                Rp {{ number_format($totalPengeluaranTransport, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CHART --}}
    <div class="row mt-4">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white font-weight-bold">
                    Grafik Pengeluaran Bulan Ini
                </div>
                <div class="card-body">
                    <canvas id="pengeluaranChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white font-weight-bold">
                    Ringkasan
                </div>
                <div class="card-body">
                    <p><strong>Invoice Belum Lunas:</strong> {{ $mitraBelumLunas }}</p>
                    <p><strong>Total Mitra:</strong> {{ $totalMitra }}</p>
                    <p><strong>Total Ex Mitra:</strong> {{ $totalExMitra }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CHART JS --}}
<script>
const ctx = document.getElementById('pengeluaranChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Internal', 'Pajak', 'Transport'],
        datasets: [{
            label: 'Pengeluaran',
            data: [
                {{ $totalPengeluaranInternal }},
                {{ $totalPengeluaranPajak }},
                {{ $totalPengeluaranTransport }}
            ],
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    }
});
</script>

@endsection
