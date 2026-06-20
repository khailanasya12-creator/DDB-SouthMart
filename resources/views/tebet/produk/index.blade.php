@extends('layouts.admin') {{-- Menyambung ke layout master buatan temanmu --}}

@section('title', 'Manajemen Produk - Node Tebet')
@section('header_title', 'SouthMart Tebet - Kelola Produk Lokal')

@section('content')
<div class="container-fluid pos-container">
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold m-0" style="color: var(--text-dark);">Daftar Produk Lokal (Node Tebet)</h5>
                <small class="text-muted">Manajemen data internal db_southmart_tebet</small>
            </div>
            {{-- Mengarahkan kembali ke halaman POS kasir utama --}}
            <a href="{{ url('/tebet/pos') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left"></i> Kembali ke POS Kasir
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="py-3 px-3">Barcode</th>
                        <th scope="col" class="py-3">Nama Produk</th>
                        <th scope="col" class="py-3 text-end">Harga Jual</th>
                        <th scope="col" class="py-3 text-center">Stok</th>
                        <th scope="col" class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="px-3"><code>{{ $product->barcode }}</code></td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-2">{{ $product->stock }}</span>
                        </td>
                        <td class="text-center">
                            {{-- Form untuk menghapus produk secara dinamis dari node lokal --}}
                            <form action="{{ route('tebet.produk.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari database lokal?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data produk di database lokal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection