@extends('layouts.admin')

@section('title', 'Inventaris Toko - SouthMart')
@section('header_title', 'SouthMart Tebet - Stok Opname')

@section('content')
<div class="container-fluid">
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="dashboard-card p-3 bg-light border-start border-primary border-3">
                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.75rem;">Total Unit Stok Terpajang</small>
                <h3 class="fw-bold m-0 mt-1" style="color: var(--text-dark);">{{ $totalStok }} <span class="fs-6 text-muted fw-normal">Pcs</span></h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-card p-3 bg-danger-subtle border-start border-danger border-3">
                <small class="text-danger d-block uppercase fw-bold" style="font-size: 0.75rem;">Produk Kritis (Stok < 5)</small>
                <h3 class="fw-bold text-danger m-0 mt-1">{{ $produkKritis }} <span class="fs-6 text-danger fw-normal">Item Barang</span></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Daftar Ketersediaan Stok Gudang Lokal</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center">Sisa Stok Terkini</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td class="fw-medium">{{ $product->name }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $product->stock < 5 ? 'bg-danger' : 'bg-success' }} rounded-2 px-3 py-1">
                                        {{ $product->stock }} Unit
                                    </span>
                                </td>
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