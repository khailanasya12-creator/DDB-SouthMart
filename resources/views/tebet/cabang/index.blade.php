@extends('layouts.admin')

@section('title', 'Daftar Cabang - SouthMart')
@section('header_title', 'SouthMart - Manajemen Cabang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="icon-box-accent icon-box-blue">
                        <i class="bi bi-shop-window"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--text-dark);">Informasi Jaringan Cabang</h5>
                        <small class="text-muted">Daftar gerai retail SouthMart yang terintegrasi dalam jaringan basis data terdistribusi</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Cabang</th>
                                <th>Nama Cabang</th>
                                <th>Alamat Operasional</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                            <tr>
                                <td><span class="badge bg-secondary rounded-2">#{{ $branch->code }}</span></td>
                                <td class="fw-semibold">{{ $branch->name }}</td>
                                <td class="text-muted">{{ $branch->address }}</td>
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