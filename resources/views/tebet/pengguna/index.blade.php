@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - SouthMart')
@section('header_title', 'SouthMart Tebet - Otentikasi Lokal')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="icon-box-accent icon-box-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--text-dark);">Daftar Pengguna & Staf Kasir</h5>
                        <small class="text-muted">Hak akses akun operator lokal yang terdaftar pada Node Cabang Tebet</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>Email/Username</th>
                                <th>Hak Akses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="fw-medium">{{ $user->name }}</td>
                                <td><code>{{ $user->email }}</code></td>
                                <td><span class="badge bg-success rounded-2">{{ $user->role ?? 'Kasir Lokal' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td class="fw-medium">Administrator Tebet</td>
                                <td><code>admin.tebet@southmart.com</code></td>
                                <td><span class="badge bg-primary rounded-2">Branch Admin</span></td>
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