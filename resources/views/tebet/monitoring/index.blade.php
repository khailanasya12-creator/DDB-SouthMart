@extends('layouts.admin')

@section('title', 'Monitoring Node - SouthMart')
@section('header_title', 'SouthMart Tebet - Status Konektivitas')

@section('content')
<div class="container-fluid">
    <div class="dashboard-card p-4">
        <h5 class="fw-bold mb-3">Status Konektivitas Jaringan Basis Data Terdistribusi</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Node / Server</th>
                        <th>IP Address Host</th>
                        <th>Nama Database</th>
                        <th class="text-center">Status Koneksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nodes as $node)
                    <tr>
                        <td class="fw-semibold">{{ $node['nama'] }}</td>
                        <td><code>{{ $node['ip'] }}</code></td>
                        <td>{{ $node['database'] }}</td>
                        <td class="text-center">
                            <span class="badge {{ $node['status'] == 'Online' ? 'bg-success' : 'bg-danger' }} rounded-2">
                                {{ $node['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection