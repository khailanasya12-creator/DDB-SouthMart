@extends('layouts.admin')

@section('title', 'Pengaturan - SouthMart')
@section('header_title', 'SouthMart Tebet - Konfigurasi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="icon-box-accent icon-box-blue">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--text-dark);">Konfigurasi Sistem Node</h5>
                        <small class="text-muted">Detail parameter env server lokal yang aktif saat ini</small>
                    </div>
                </div>

                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <span class="fw-medium text-muted">Identitas Node</span>
                        <span class="fw-bold" style="color: var(--text-dark);">{{ $config['node_name'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <span class="fw-medium text-muted">Nama Aplikasi Utama</span>
                        <span class="fw-bold">{{ $config['app_name'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <span class="fw-medium text-muted">Environment Mode</span>
                        <span class="badge bg-warning text-dark border border-warning-subtle rounded-2 px-2 py-1">{{ strtoupper($config['env']) }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <span class="fw-medium text-muted">Driver Koneksi Default</span>
                        <span class="text-primary fw-mono"><code>{{ $config['connection_default'] }}</code></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection