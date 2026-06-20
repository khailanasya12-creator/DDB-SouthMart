@extends('layouts.admin') {{-- Menyambung ke layout master admin --}}

@section('title', 'Point of Sales (POS) Tebet') {{-- Mengisi yield title di layout master --}}
@section('header_title', 'SouthMart Tebet - POS Kasir') {{-- Mengubah judul header atas --}}

@section('styles')
<style>
    /* Kustomisasi tampilan agar komponen kartu dan tabel senada dengan desain master */
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
    .cart-item-list {
        max-height: 150px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="container-fluid pos-container">
    {{-- Alert Notifikasi Sukses/Gagal Transaksi atau Input Barang --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- SISI KIRI: PEMILIHAN PRODUK (KATALOG REPLIKASI LOKAL TEBET) --}}
        <div class="col-lg-7">
            <div class="dashboard-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
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

                {{-- FITUR BARU: Tombol Pemanggil Modal Form CRUD Tambah Produk Baru --}}
                <div class="mb-4">
                    <button type="button" class="btn btn-sm btn-success rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Produk Baru ke Node
                    </button>
                </div>

                {{-- Kolom Input Simulasi Scanner Barcode --}}
                <div class="input-group mb-4 shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 py-2.5" placeholder="Scan barcode produk atau ketik manual di sini..." autofocus>
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
                                <td class="px-3 fw-mono text-muted" style="font-size: 0.8rem;"><code>{{ $product->barcode }}</code></td>
                                <td>
                                    <span class="fw-semibold d-block" style="color: var(--text-dark);">{{ $product->name }}</span>
                                </td>
                                <td class="text-end fw-medium text-money">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($product->stock > 5)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-2.5 py-1">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2 px-2.5 py-1">{{ $product->stock }} Menipis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary px-3 rounded-2" onclick="tambahKeKeranjang({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                        <i class="bi bi-plus-lg"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                            @empty
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

        {{-- SISI KANAN: DETAIL TRANSAKSI AKTIF & CHECKOUT FORM --}}
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

                    {{-- List Tampilan Barang yang Sedang Dipilih --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Produk yang Dipilih:</label>
                        <div id="list-keranjang-produk" class="list-group cart-item-list border rounded p-2 bg-white">
                            <span class="text-muted small text-center d-block py-2">Belum ada produk yang dipilih</span>
                        </div>
                    </div>

                    {{-- Bagian Tampilan Total Belanja --}}
                    <div class="bg-light p-3 rounded-3 mb-4 border border-light-subtle">
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 0.85rem;">
                            <span>Subtotal Belanja</span>
                            <span id="tampilan-subtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 0.85rem;">
                            <span>Pajak & Ritel Fee (0%)</span>
                            <span>Rp 0</span>
                        </div>
                        <hr class="my-2 border-secondary-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="color: var(--text-dark);">Grand Total</span>
                            <span class="fs-4 fw-bold text-money" id="tampilan-grandtotal" style="color: var(--primary-accent);">Rp 0</span>
                        </div>
                    </div>

                    {{-- Form Utama Penyimpanan Data Terdistribusi --}}
                    <form action="{{ route('tebet.pos.checkout') }}" method="POST">
                        @csrf
                        
                        <div id="hidden-inputs-container"></div>
                        <input type="hidden" name="grand_total" id="input-grandtotal" value="0">

                        <div class="mb-4">
                            <label class="form-label fw-medium text-muted small">Pilih Metode Pembayaran</label>
                            <select name="payment_method" class="form-select border-secondary-subtle py-2" style="font-size: 0.9rem;">
                                <option value="Cash">Tunai (Cash)</option>
                                <option value="QRIS">QRIS Nasional</option>
                                <option value="Debit">Kartu Debit</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-accent w-100 py-3 rounded-3 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-check-all fs-5"></i>
                            <span class="fw-semibold">PROSES TRANSAKSI SEKARANG</span>
                        </button>
                    </form>
                </div>

                {{-- Edukasi Konsep SBDT --}}
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

{{-- MODAL POPUP CRUD: Input Produk Baru Langsung Dari Aplikasi --}}
<div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahProdukLabel">Input Produk Baru (Node Tebet)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tebet.pos.store-product') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Barcode Produk</label>
                        <input type="text" name="barcode" class="form-control" placeholder="Contoh: 8999906102006" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Nama Produk</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Sari Roti Tawar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Harga Jual (Rp)</label>
                        <input type="number" name="price" class="form-control" placeholder="Contoh: 15000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Stok Awal di Tebet</label>
                        <input type="number" name="stock" class="form-control" placeholder="Contoh: 50" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan ke Database</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT: Logika Keranjang Akumulatif dan Pemotong Stok --}}
<script>
    let keranjang = [];
    let totalHarga = 0;

    function tambahKeKeranjang(id, name, price) {
        let produkEksis = keranjang.find(item => item.id === id);

        if (produkEksis) {
            produkEksis.qty += 1;
        } else {
            keranjang.push({ id: id, name: name, price: price, qty: 1 });
        }

        updateTampilanKeranjang();
    }

    function updateTampilanKeranjang() {
        let containerList = document.getElementById('list-keranjang-produk');
        let hiddenContainer = document.getElementById('hidden-inputs-container');
        
        containerList.innerHTML = ''; 
        hiddenContainer.innerHTML = ''; 
        
        totalHarga = 0;

        keranjang.forEach(item => {
            let subtotalItem = item.price * item.qty;
            totalHarga += subtotalItem;

            let itemHtml = `
                <div class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom py-1.5 px-1 fs-7">
                    <div>
                        <span class="fw-semibold text-dark d-block">${item.name}</span>
                        <small class="text-muted">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</small>
                    </div>
                    <span class="fw-bold text-slate">Rp ${subtotalItem.toLocaleString('id-ID')}</span>
                </div>
            `;
            containerList.innerHTML += itemHtml;

            hiddenContainer.innerHTML += `<input type="hidden" name="product_ids[]" value="${item.id}">`;
            hiddenContainer.innerHTML += `<input type="hidden" name="quantities[]" value="${item.qty}">`;
        });

        document.getElementById('input-grandtotal').value = totalHarga;
        document.getElementById('tampilan-subtotal').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
        document.getElementById('tampilan-grandtotal').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
    }
</script>
@endsection