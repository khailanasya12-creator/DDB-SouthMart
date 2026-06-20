@extends('layouts.admin')

@section('title', 'Query Lintas Node - SouthMart')
@section('header_title', 'SouthMart Tebet - Remote Query')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="icon-box-accent icon-box-blue">
                        <i class="bi bi-database-fill-gear"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--text-dark);">Eksekusi Remote Query (Lintas Node)</h5>
                        <small class="text-muted">Menarik dan menampilkan katalog data produk master langsung dari Server Pusat (db_southmrt)</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Barcode Master</th>
                                <th>Nama Produk Pusat</th>
                                <th class="text-end">Harga Standar Pusat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($globalProducts as $product)
                            <tr>
                                <td class="text-muted"><code>{{ $product->barcode }}</code></td>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td class="text-end fw-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-wifi-off fs-2 d-block mb-2"></i> Gagal melakukan remote query. Pastikan database server pusat dalam kondisi aktif!
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