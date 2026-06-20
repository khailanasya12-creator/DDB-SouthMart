@extends('layouts.admin')

@section('title', 'Laporan Analitis - SouthMart')
@section('header_title', 'SouthMart Tebet - Keuangan Cabang')

@section('content')
<div class="container-fluid">
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="dashboard-card p-3 bg-success text-white">
                <small class="text-white-50 d-block uppercase fw-bold" style="font-size: 0.75rem;">Total Omset Pendapatan Lokal</small>
                <h3 class="fw-bold m-0 mt-1">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-card p-3 bg-dark text-white">
                <small class="text-white-50 d-block uppercase fw-bold" style="font-size: 0.75rem;">Volume Transaksi Sukses</small>
                <h3 class="fw-bold m-0 mt-1">{{ $totalTransaksi }} <span class="fs-6 text-white-50 fw-normal">Kali Checkout</span></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Jurnal Riwayat Penjualan Kasir</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Cetak Nota</th>
                                <th>Nomor Invoice</th>
                                <th class="text-end">Nilai Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $t)
                            <tr>
                                <td class="text-muted small">{{ $t->created_at }}</td>
                                <td><code>{{ $t->invoice }}</code></td>
                                <td class="text-end fw-semibold text-success">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection