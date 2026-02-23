@extends('layouts.app')

@section('content')

<div class="container-fluid">

  {{-- FILTER BULAN --}}
  <div class="card shadow-sm mb-4">
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
                <button class="btn btn-primary px-4">
                    <i class="fas fa-filter mr-1"></i>
                    Filter
                </button>

                <div style="width:12px"></div> {{-- SPACER --}}

                <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary px-4">
                    Reset
                </a>
            </div>

        </form>
    </div>
  </div>

  {{-- PAGE HEADING --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-gray-800">Dashboard Owner Transport</h1>
      <span class="badge badge-primary px-3 py-2">
          {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
      </span>
  </div>

  {{-- STAT CARDS --}}
  <div class="row">

      {{-- MITRA BELUM LUNAS --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-danger">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Invoice Mitra Belum Lunas</div>
                      <div class="h4 font-weight-bold mt-2">{{ $mitraBelumLunas }}</div>
                      <small>Mitra</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-user-times"></i></div>
              </div>
          </div>
      </div>

      {{-- TOTAL MITRA --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-primary">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Total Mitra</div>
                      <div class="h4 font-weight-bold mt-2">{{ $totalMitra }}</div>
                      <small>Semua Partner</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-users"></i></div>
              </div>
          </div>
      </div>

      {{-- MITRA AKTIF --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-success">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Mitra Aktif</div>
                      <div class="h4 font-weight-bold mt-2">{{ $mitraAktif }}</div>
                      <small>Partner Aktif</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-user-check"></i></div>
              </div>
          </div>
      </div>

      {{-- MITRA BERAKHIR --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-dark">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Ex - Mitra</div>
                      <div class="h4 font-weight-bold mt-2">{{ $mitraBerakhir }}</div>
                      <small>Non Aktif</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-user-times"></i></div>
              </div>
          </div>
      </div>

      {{-- PENGELUARAN INTERNAL --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-warning">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Pengeluaran Internal</div>
                      <div class="h4 font-weight-bold mt-2">Rp {{ number_format($totalPengeluaranInternal, 0, ',', '.') }}</div>
                      <small>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-wallet"></i></div>
              </div>
          </div>
      </div>


      {{-- PENGELUARAN TRANSPORT --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-danger">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Pengeluaran Transport</div>
                      <div class="h4 font-weight-bold mt-2">Rp {{ number_format($totalPengeluaranTransport, 0, ',', '.') }}</div>
                      <small>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-truck"></i></div>
              </div>
          </div>
      </div>

       {{-- PENGELUARAN PAJAK --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-warning">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Pengeluaran Pajak Mobil</div>
                      <div class="h4 font-weight-bold mt-2">Rp {{ number_format($totalPengeluaranPajak, 0, ',', '.') }}</div>
                      <small>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-car"></i></div>
              </div>
          </div>
      </div>

      {{-- PEMASUKAN HARIAN --}}
      <div class="col-xl-3 col-md-6 mb-4">
          <div class="card dashboard-card bg-gradient-success">
              <div class="card-body text-white d-flex justify-content-between align-items-center">
                  <div>
                      <div class="text-uppercase small font-weight-bold">Pemasukan Hari Ini</div>
                      <div class="h4 font-weight-bold mt-2">Rp {{ number_format($totalPemasukanHarian, 0, ',', '.') }}</div>
                      <small>{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</small>
                  </div>
                  <div class="icon-circle"><i class="fas fa-coins"></i></div>
              </div>
          </div>
      </div>

  </div>

  {{-- RINGKASAN BULANAN --}}
<div class="row mt-4">

    {{-- PENGELUARAN (KIRI) --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card dashboard-card bg-gradient-secondary">
            <div class="card-body text-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small font-weight-bold">Pengeluaran Bulanan</div>
                    <div class="h4 font-weight-bold mt-2">
                        Rp {{ number_format($totalPengeluaranBulanan, 0, ',', '.') }}
                    </div>
                    <small>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</small>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>


{{-- STATUS LABA/RUGI (TENGAH) --}}
<div class="col-xl-4 col-md-6 mb-4">
    @php
        $selisih = $totalPemasukanBulanan - $totalPengeluaranBulanan;

        if($totalPemasukanBulanan > 0){
            $persen = ($selisih / $totalPemasukanBulanan) * 100;
        } else {
            $persen = 0;
        }

        $status = $selisih >= 0 ? 'Laba' : 'Rugi';
    @endphp

    <style>
        .dashboard-card{
            height:140px;
        }

        .dashboard-card .card-body{
            height:100%;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        /* area teks fleksibel */
        .dashboard-card .info{
            flex:1;
            min-width:0;
        }

        /* nominal boleh turun baris */
        .dashboard-card .value{
            font-size:20px;
            font-weight:700;
            line-height:1.25;
            word-break:break-word;
        }

        .dashboard-card .percent{
            font-size:12px;
            font-weight:600;
            opacity:.85;
        }

        /* icon tidak boleh mengecil */
        .icon-circle{
            width:55px;
            height:55px;
            min-width:55px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            background:rgba(255,255,255,.2);
            font-size:22px;
            flex-shrink:0;
        }
    </style>

    <div class="card dashboard-card {{ $selisih >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
        <div class="card-body text-white">

            <div class="info">
                <div class="text-uppercase small font-weight-bold">
                    Status Laba/Rugi Bulan Ini
                </div>

                <div class="value mt-1">
                    {{ $status }}: Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                    <span class="percent">
                        ({{ number_format(abs($persen),2) }}%)
                    </span>
                </div>

                <small>
                    {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
                </small>
            </div>

            <div class="icon-circle">
                <i class="fas fa-chart-line"></i>
            </div>

        </div>
    </div>
</div>


    {{-- PEMASUKAN (KANAN) --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card dashboard-card bg-gradient-info">
            <div class="card-body text-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase small font-weight-bold">Pemasukan Bulanan</div>
                    <div class="h4 font-weight-bold mt-2">
                        Rp {{ number_format($totalPemasukanBulanan, 0, ',', '.') }}
                    </div>
                    <small>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</small>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

</div>
{{-- ================= RECENT TRANSACTIONS CLEAN ================= --}}
<div class="row mt-4 g-4">

    {{-- PEMASUKAN --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100 border-0 recent-card">
            <div class="card-header bg-white fw-semibold text-success">
                <i class="fas fa-coins me-2"></i> Pemasukan Terbaru
            </div>

            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentPemasukan as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">
                                {{ $item->mitra->nama_mitra ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </div>
                        </div>
                        <span class="fw-bold text-success">
                            Rp {{ number_format($item->nominal,0,',','.') }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted small">
                        Tidak ada data
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- INTERNAL --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100 border-0 recent-card">
            <div class="card-header bg-white fw-semibold text-danger">
                <i class="fas fa-building me-2"></i> Internal Terbaru
            </div>

            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentPengeluaranInternal as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </span>
                        <span class="fw-bold text-danger">
                            Rp {{ number_format($item->nominal,0,',','.') }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted small">
                        Tidak ada data
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- TRANSPORT --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100 border-0 recent-card">
            <div class="card-header bg-white fw-semibold text-warning">
                <i class="fas fa-truck me-2"></i> Transport Terbaru
            </div>

            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentPengeluaranTransport as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </span>
                        <span class="fw-bold text-danger">
                            Rp {{ number_format($item->total_amount,0,',','.') }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted small">
                        Tidak ada data
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- PAJAK --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100 border-0 recent-card">
            <div class="card-header bg-white fw-semibold text-secondary">
                <i class="fas fa-file-invoice-dollar me-2"></i> Pajak Terbaru
            </div>

            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentPengeluaranPajak as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </span>
                        <span class="fw-bold text-danger">
                            Rp {{ number_format($item->nominal,0,',','.') }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted small">
                        Tidak ada data
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>
<style>
.recent-card {
    border-radius: 12px;
}

.recent-card .card-header {
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.recent-card .list-group-item {
    padding: 12px 16px;
    font-size: 13px;
}

.recent-card .list-group-item:hover {
    background-color: #f8f9fa;
}
</style>

  {{-- CHART --}}
<div class="row mt-4">

    {{-- PEMASUKAN BULAN --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white fw-semibold">
                Grafik Pemasukan Bulan Ini
            </div>
            <div class="card-body">
                <canvas id="pemasukanBulananChart"></canvas>
            </div>
        </div>
    </div>

    {{-- PENGELUARAN BULAN --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white fw-semibold">
                Grafik Pengeluaran Bulan Ini
            </div>
            <div class="card-body">
                <canvas id="pengeluaranBulananChart"></canvas>
            </div>
        </div>
    </div>

    {{-- PEMASUKAN TAHUN --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white fw-semibold">
                Grafik Pemasukan Tahun {{ $tahun }}
            </div>
            <div class="card-body">
                <canvas id="pemasukanTahunanChart"></canvas>
            </div>
        </div>
    </div>

    {{-- PENGELUARAN TAHUN --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white fw-semibold">
                Grafik Pengeluaran Tahun {{ $tahun }}
            </div>
            <div class="card-body">
                <canvas id="pengeluaranTahunanChart"></canvas>
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

<script>
document.addEventListener("DOMContentLoaded", function () {

    let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // FILTER -> tampilkan loading
    document.getElementById('filterForm').addEventListener('submit', function () {
        loadingModal.show();
    });

    // DETAIL -> tampilkan loading lalu redirect
    document.querySelectorAll('.btn-detail').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            loadingModal.show();

            let url = this.getAttribute('href');

            setTimeout(function(){
                window.location.href = url;
            }, 400);
        });
    });

});
</script>

<script>

/* ================= BULANAN ================= */

// PEMASUKAN BULANAN
new Chart(document.getElementById('pemasukanBulananChart'), {
    type: 'bar',
    data: {
        labels: ['Pemasukan'],
        datasets: [{
            label: 'Rp',
            data: [{{ $totalPemasukanBulanan }}],
            backgroundColor: '#28a745'
        }]
    },
    options: chartCurrencyOption()
});

// PENGELUARAN BULANAN
new Chart(document.getElementById('pengeluaranBulananChart'), {
    type: 'bar',
    data: {
        labels: ['Internal','Pajak','Transport'],
        datasets: [{
            label: 'Rp',
            data: [
                {{ $totalPengeluaranInternal }},
                {{ $totalPengeluaranPajak }},
                {{ $totalPengeluaranTransport }}
            ],
            backgroundColor: '#dc3545'
        }]
    },
    options: chartCurrencyOption()
});


/* ================= TAHUNAN ================= */

// PEMASUKAN TAHUNAN
new Chart(document.getElementById('pemasukanTahunanChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Pemasukan',
            data: {!! json_encode($pemasukanTahunan) !!},
            borderColor: '#28a745',
            backgroundColor: 'rgba(40,167,69,0.15)',
            tension: 0.3,
            fill: true
        }]
    },
    options: chartCurrencyOption()
});

// PENGELUARAN TAHUNAN
new Chart(document.getElementById('pengeluaranTahunanChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Pengeluaran',
            data: {!! json_encode($pengeluaranTahunan) !!},
            borderColor: '#dc3545',
            backgroundColor: 'rgba(220,53,69,0.15)',
            tension: 0.3,
            fill: true
        }]
    },
    options: chartCurrencyOption()
});


/* ================= FORMAT RUPIAH ================= */
function chartCurrencyOption(){
    return {
        responsive:true,
        plugins:{
            tooltip:{
                callbacks:{
                    label:function(ctx){
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                    }
                }
            }
        },
        scales:{
            y:{
                ticks:{
                    callback:(v)=>'Rp '+ new Intl.NumberFormat('id-ID').format(v)
                }
            }
        }
    }
}

</script>
  @endsection
