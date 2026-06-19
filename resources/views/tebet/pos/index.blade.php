@extends('layouts.admin') {{-- Menyambung ke layout master buatan temanmu --}}

@section('title', 'Point of Sales (POS) Tebet') {{-- Mengisi yield title di layout master --}}
@section('header_title', 'SouthMart Tebet - POS Kasir') {{-- Mengubah judul header atas --}}

@section('styles')
<style>
    /* Kustomisasi tambahan agar komponen kartu dan tabel senada dengan desain master */
    .pos-container {
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
    .btn-accent {
        background-color: var(--primary-accent);
        color: #FFFFFF;
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        transition: background-color 0.2s;
    }
    .btn-accent:hover {
        background-color: var(--secondary-accent);
        color: #FFFFFF;
    }
    .text-money {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        color: var(--text-dark);
    }
</style>
@endsection

@section('content')
<div class="container-fluid pos-container">
    <div class="row g-4">
        
        {{-- SISI KIRI: PEMILIHAN PRODUK (KATALOG REPLIKASI LOKAL TEBET) --}}
        <div class="col-lg-7">
            <div class="dashboard-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="icon-box-accent icon-box-blue">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--text-dark);">Katalog Produk Lokal</h5>
                            <small class="text-muted">Data hasil replikasi dari server pusat</small>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-medium">Node Tebet</span>
                </div>

                {{-- Kolom Input Simulasi Scanner Barcode --}}
                <div class="input-group mb-4 shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 py-2.5" placeholder="Scan barcode produk atau ketik manual di sini..." autofocus> {{-- Fokus otomatis ketika halaman dimuat --}}
                </div>

                {{-- Tabel Daftar Produk Lokal --}}
                <div class="table-responsive">
                    <table class="table table-hover table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="px-3 py-3">Barcode</th>
                                <th scope="col" class="py-3">Nama Produk</th>
                                <th scope="col" class="py-3 text-end">Harga Jual</th>
                                <th scope="col" class="py-3 text-center">Stok Lokal</th>
                                <th scope="col" class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product) {{-- Iterasi data produk dari database lokal --}}
                            <tr>
                                {{-- DISESUAIKAN: Menampilkan kode barcode sesuai database --}}
                                <td class="px-3 fw-mono text-muted" style="font-size: 0.8rem;"><code>{{ $product->barcode }}</code></td>
                                <td>
                                    <span class="fw-semibold d-block" style="color: var(--text-dark);">{{ $product->name }}</span>
                                </td>
                                {{-- DISESUAIKAN: Mengubah dari price_sell menjadi price sesuai kolom migration kamu --}}
                                <td class="text-end fw-medium text-money">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($product->stock > 5)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-2.5 py-1">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2 px-2.5 py-1">{{ $product->stock }} Menipis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Tombol simulasi aksi memasukkan barang ke keranjang kasir --}}
                                    <button class="btn btn-sm btn-outline-primary px-3 rounded-2" onclick="alert('Fitur keranjang interaktif siap dikembangkan!')">
                                        <i class="bi bi-plus-lg"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                            @empty {{-- Jika ternyata data seeder kosong --}}
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inboxes fs-1 d-block mb-2"></i> Belum ada data produk di database lokal.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: RINGKASAN KERANJANG & CHECKOUT DISTRIBUSI --}}
        <div class="col-lg-5">
            <div class="dashboard-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="icon-box-accent icon-box-sky">
                            <i class="bi bi-cart-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--text-dark);">Detail Transaksi Aktif</h5>
                            <small class="text-muted">Simulasi kalkulasi nilai belanja</small>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4 border border-light-subtle">
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 0.85rem;">
                            <span>Subtotal Belanja</span>
                            <span>Rp 150.000</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 0.85rem;">
                            <span>Pajak & Ritel Fee (0%)</span>
                            <span>Rp 0</span>
                        </div>
                        <hr class="my-2 border-secondary-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="color: var(--text-dark);">Grand Total</span>
                            <span class="fs-4 fw-bold text-money" style="color: var(--primary-accent);">Rp 150.000</span>
                        </div>
                    </div>

                    {{-- Form Utama Penyimpanan Data Terdistribusi --}}
                    <form action="{{ route('tebet.pos.checkout') }}" method="POST">
                        @csrf {{-- Token wajib proteksi serangan CSRF Laravel --}}
                        
                        {{-- Menyimpan nilai total belanja rahasia untuk dikirim ke backend controller --}}
                        <input type="hidden" name="grand_total" value="150000">

                        <div class="mb-4">
                            <label class="form-label fw-medium text-muted small">Pilih Metode Pembayaran</label>
                            <select class="form-select border-secondary-subtle py-2" style="font-size: 0.9rem;">
                                <option value="cash">Tunai (Cash)</option>
                                <option value="qris">QRIS Nasional</option>
                                <option value="debit">Kartu Debit</option>
                            </select>
                        </div>

                        {{-- Tombol Utama Penyimpanan Transaksi Terdistribusi --}}
                        <button type="submit" class="btn btn-accent w-100 py-3 rounded-3 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-check-all fs-5"></i>
                            <span class="fw-semibold">PROSES TRANSAKSI SEKARANG</span>
                        </button>
                    </form>
                </div>

                {{-- Edukasi Konsep SBDT untuk Kebutuhan Demonstrasi Sidang Tugas Akhir/Mata Kuliah --}}
                <div class="card bg-slate border-0 rounded-3 shadow-sm p-3 mt-auto" style="background-color: #F8FAFC; border: 1px solid var(--border-color) !important;">
                    <div class="d-flex gap-2.5">
                        <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                        <div>
                            <span class="fw-bold d-block text-dark small mb-1">Mekanisme Penyimpanan Data SBDT:</span>
                            <ul class="list-unstyled m-0 text-muted" style="font-size: 0.8rem; line-height: 1.5;">
                                <li class="d-flex align-items-start gap-1 mb-1">
                                    <span class="text-success">✔</span>
                                    <span>Data disimpan lokal di <strong>db_southmart_tebet</strong> (Fragmentasi Horizontal).</span>
                                </li>
                                <li class="d-flex align-items-start gap-1">
                                    <span class="text-warning">⚡</span>
                                    <span>Status transaksi diset ke <strong>'Pending Sync'</strong> sebelum ditarik server pusat.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection