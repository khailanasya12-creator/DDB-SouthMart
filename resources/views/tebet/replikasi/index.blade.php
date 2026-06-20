@extends('layouts.admin') {{-- Menyambung ke layout master admin kelompokmu --}}

@section('title', 'Replikasi & Konsistensi Data - Node Tebet')
@section('header_title', 'SouthMart Tebet - Sinkronisasi Data')

@section('styles')
<style>
    /* Style kustom agar font dan layout konsisten dengan modul POS kasir */
    .replikasi-container {
        font-family: 'Inter', sans-serif;
    }
    .table-modern thead th {
        background-color: #F1F5F9;
        color: var(--text-dark);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
    }
    .table-modern tbody td {
        vertical-align: middle;
        font-size: 0.875rem;
        color: var(--text-dark);
    }
</style>
@endsection

@section('content')
<div class="container-fluid replikasi-container">
    <div class="row">
        <div class="col-12">
            {{-- Menggunakan class dashboard-card bawaan template agar lurus dengan sidebar --}}
            <div class="dashboard-card p-4">
                
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="icon-box-accent icon-box-sky">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--text-dark);">Log Replikasi & Konsistensi Data</h5>
                            <small class="text-muted">Sinkronisasi data tabel transactions lokal ke server pusat (db_southmrt)</small>
                        </div>
                    </div>
                    
                    {{-- Form Tombol untuk Memicu Proses Replikasi / Sinkronisasi --}}
                    <form action="{{ route('tebet.replikasi.sync') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Sinkronisasikan Data Sekarang
                        </button>
                    </form>
                </div>

                {{-- Alert Info Sukses atau Gagal Sinkronisasi --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2" role="alert">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-warning alert-dismissible fade show rounded-3 small py-2" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Tabel Daftar Log Transaksi Node Tebet --}}
                <div class="table-responsive">
                    <table class="table table-hover table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="px-3 py-3">Waktu Transaksi</th>
                                <th scope="col" class="py-3">No. Invoice</th>
                                <th scope="col" class="py-3 text-end">Total Nominal</th>
                                <th scope="col" class="py-3 text-center">Status Konsistensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td class="px-3 text-muted small">{{ $trx->created_at }}</td>
                                <td class="fw-semibold"><code>{{ $trx->invoice }}</code></td>
                                <td class="text-end fw-medium">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($trx->status == 'Pending Sync')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-2 px-2.5 py-1">
                                            <i class="bi bi-clock-history"></i> Pending Sync (Lokal Node)
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-2.5 py-1">
                                            <i class="bi bi-cloud-check-fill"></i> Synced to HQ (Pusat)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-cloud-slash fs-2 d-block mb-2"></i> Belum ada riwayat data transaksi di database lokal cabang Tebet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection