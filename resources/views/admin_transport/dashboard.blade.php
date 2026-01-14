@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Dashboard Admin Transport
    </h1>

    <div class="row">

        <!-- Invoice Belum Lunas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Invoice Belum Lunas
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $invoiceBelumLunas }} Invoice
                    </div>
                    <small class="text-muted">
                        Rp {{ number_format($totalInvoiceBelumLunas, 0, ',', '.') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Total Mitra -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Mitra
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $totalMitra }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Internal -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Pengeluaran Internal
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp {{ number_format($totalPengeluaranInternal, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pajak Mobil -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Pajak Mobil
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp {{ number_format($totalPengeluaranPajak, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Transport -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Pengeluaran Transport
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp {{ number_format($totalPengeluaranTransport, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
