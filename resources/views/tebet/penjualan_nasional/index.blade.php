@extends('layouts.admin')

@section('title', 'Penjualan Nasional - SouthMart')
@section('header_title', 'SouthMart Tebet - Konsolidasi Data')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="icon-box-accent icon-box-blue">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--text-dark);">Konsolidasi Penjualan Nasional</h5>
                        <small class="text-muted">Hasil penggabungan (Union Query) data terfragmentasi horizontal antara lokal dan pusat</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Asal Node Database</th>
                                <th>Waktu Transaksi</th>
                                <th>No. Invoice</th>
                                <th class="text-end">Total Nominal Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($globalTransactions as $trx)
                            <tr>
                                <td>
                                    <span class="badge {{ $trx->lokasi == 'Server Pusat (HQ)' ? 'bg-primary' : 'bg-info' }} rounded-2">
                                        {{ $trx->lokasi }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $trx->created_at }}</td>
                                <td class="fw-semibold"><code>{{ $trx->invoice }}</code></td>
                                <td class="text-end fw-medium">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
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