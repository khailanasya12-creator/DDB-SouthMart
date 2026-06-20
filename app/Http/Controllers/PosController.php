<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Digunakan untuk menjalankan query database manual terdistribusi

class PosController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & MENU UTAMA POS KASIR
    // ==========================================

    // Fungsi untuk menampilkan halaman utama POS Kasir Tebet
    public function index()
    {
        // Mengambil data produk dari database lokal Tebet
        $products = \DB::connection('node_tebet')->table('products')->get();
        
        // Mengarah ke folder tebet paling depan -> folder pos -> file index.blade.php
        return view('tebet.pos.index', compact('products'));
    }

    // Fungsi untuk memproses transaksi penjualan (Checkout) di kasir Tebet
    public function checkout(Request $request)
    {
        // Mengambil data metode pembayaran dan total harga dari input form
        $paymentMethod = $request->input('payment_method');
        $grandTotal = $request->input('grand_total');

        // VALIDASI: Pastikan kasir sudah memilih produk (grand total tidak boleh 0)
        if ($grandTotal <= 0) {
            return redirect()->back()->with('error', 'Keranjang masih kosong! Pilih produk terlebih dahulu.');
        }

        // MEMULAI DATABASE TRANSACTION: Dipaksa menggunakan koneksi 'node_tebet' agar menjaga konsistensi data cabang
        \DB::connection('node_tebet')->beginTransaction();

        try {
            // AMBIL DATA DARI INPUT HIDDEN BARANG (Data ID dan Qty yang dikirim oleh JavaScript)
            $productIds = $request->input('product_ids', []);
            $quantities = $request->input('quantities', []);

            // LOOPING BARANG: Mengurangi stok masing-masing barang yang dibeli di database cabang
            foreach ($productIds as $index => $id) {
                $qtyDibeli = $quantities[$index];

                // Menambahkan connection('node_tebet') agar mencari produk di database tebet, bukan db_southmrt
                $currentProduct = \DB::connection('node_tebet')->table('products')->where('id', $id)->first();

                if ($currentProduct) {
                    // Cek apakah stok di database lokal mencukupi untuk transaksi ini
                    if ($currentProduct->stock < $qtyDibeli) {
                        \DB::connection('node_tebet')->rollBack(); // Batalkan transaksi jika stok kurang
                        return redirect()->back()->with('error', 'Stok untuk produk ' . $currentProduct->name . ' tidak mencukupi!');
                    }

                    // Menambahkan connection('node_tebet') agar pemotongan stok dilakukan di tabel tebet yang benar
                    \DB::connection('node_tebet')->table('products')->where('id', $id)->decrement('stock', $qtyDibeli);
                }
            }

            // Menambahkan connection('node_tebet') agar nota belanja tersimpan di tabel transactions milik tebet
            \DB::connection('node_tebet')->table('transactions')->insert([
                'invoice'     => 'INV-TBT-' . time(), // Membuat nomor invoice unik otomatis
                'grand_total' => $grandTotal,         // Menyimpan total nominal uang belanjaan
                'status'      => 'Pending Sync',      // Flag penanda data distribusi fragmentasi horizontal
                'created_at'  => now(),               // Waktu transaksi dibuat
                'updated_at'  => now()                // Waktu transaksi diupdate
            ]);

            // JIKA SEMUA PROSES BERHASIL: Commit data fisik ke database node tebet
            \DB::connection('node_tebet')->commit();

            return redirect()->back()->with('success', 'Transaksi Berhasil! Stok produk lokal otomatis berkurang.');

        } catch (\Exception $e) {
            // JIKA ERROR: Batalkan semua perubahan stok di node tebet agar data tetap konsisten
            \DB::connection('node_tebet')->rollBack();
            return redirect()->back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 2. MENU MANAJEMEN PRODUK (CRUD)
    // ==========================================

    // Fungsi untuk menampilkan daftar produk cabang
    public function indexProduk()
    {
        // Mengambil data produk dari database lokal Tebet
        $products = \DB::connection('node_tebet')->table('products')->get();
        
        // Mengarah ke folder tebet paling depan -> folder produk -> file index.blade.php
        return view('tebet.produk.index', compact('products'));
    }

    // Fungsi untuk memproses penambahan produk baru secara dinamis lewat form CRUD aplikasi
    public function storeProduct(Request $request)
    {
        // Menambahkan ->connection('node_tebet') agar data masuk ke database southmart_tebet, bukan db_southmrt
        \DB::connection('node_tebet')->table('products')->insert([
            'barcode'    => $request->barcode,
            'name'       => $request->name,
            'price'      => $request->price,
            'stock'      => $request->stock,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Produk Baru Berhasil Ditambahkan Secara Dinamis ke Database!');
    }

    // Fungsi untuk menghapus salah satu produk secara dinamis dari database lokal Tebet
    public function destroyProduk($id)
    {
        // Menghapus data berdasarkan ID produk pada koneksi lokal tebet
        \DB::connection('node_tebet')->table('products')->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Produk berhasil dihapus secara dinamis dari database lokal!');
    }

    // ==========================================
    // 3. MENU MONITORING CABANG (DINAMIS)
    // ==========================================

    public function indexMonitoring()
    {
        // Daftar konfigurasi database node yang ingin dicek secara dinamis
        $connections = [
            ['nama' => 'Server Pusat (HQ)', 'koneksi' => 'mysql', 'database' => 'db_southmrt'],
            ['nama' => 'Node Tebet (Lokal)', 'koneksi' => 'node_tebet', 'database' => 'southmart_tebet'],
            ['nama' => 'Node Kemang', 'koneksi' => 'node_kemang', 'database' => 'southmart_kemang'],
            ['nama' => 'Node Bogor', 'koneksi' => 'node_bogor', 'database' => 'southmart_bogor'],
        ];

        $nodes = [];

        // Looping untuk mengecek status keaktifan masing-masing database node secara riil
        foreach ($connections as $conn) {
            try {
                // Memicu koneksi PDO Laravel secara paksa untuk mengetes apakah database tujuan merespons
                \DB::connection($conn['koneksi'])->getPdo();
                $status = 'Online'; // Jika tidak terjadi crash/error, maka status node diset Online
            } catch (\Exception $e) {
                $status = 'Offline'; // Jika koneksi timeout atau gagal terhubung, otomatis dianggap Offline
            }

            // Mengambil data host IP secara dinamis dari file konfigurasi .env proyekmu
            $ipHost = config("database.connections.{$conn['koneksi']}.host", '127.0.0.1');

            // Memasukkan hasil pengecekan riil ke dalam array untuk dikirim ke halaman Blade
            $nodes[] = [
                'nama'     => $conn['nama'],
                'ip'       => $ipHost,
                'database' => $conn['database'],
                'status'   => $status
            ];
        }

        // Mengirimkan data hasil pengecekan koneksi database terdistribusi ke halaman view monitoring
        return view('tebet.monitoring.index', compact('nodes'));
    }

    // ==========================================
    // 4. MENU PENJUALAN NASIONAL (UNION)
    // ==========================================

    public function indexPenjualanNasional()
    {
        // 1. Ambil data transaksi dari database lokal Tebet
        $lokalTrx = \DB::connection('node_tebet')->table('transactions')->select('invoice', 'grand_total', 'status', 'created_at')->get()->map(function($item) {
            $item->lokasi = 'Node Tebet (Lokal)'; // Menandai asal data
            return $item;
        });
        
        // 2. Ambil data transaksi dari Server Pusat dengan proteksi try-catch agar tidak crash jika pusat offline
        try {
            $pusatTrx = \DB::connection('mysql')->table('transactions')->select('invoice', 'grand_total', 'status', 'created_at')->get()->map(function($item) {
                $item->lokasi = 'Server Pusat (HQ)'; // Menandai asal data
                return $item;
            });
            // PERBAIKAN: Menyelesaikan proses UNION penggabungan data yang menggantung tadi
            $globalTransactions = $lokalTrx->merge($pusatTrx)->sortByDesc('created_at');
        } catch (\Exception $e) {
            $globalTransactions = $lokalTrx; // Fail-over jika database pusat offline
        }

        return view('tebet.penjualan_nasional.index', compact('globalTransactions'));
    }

    // ==========================================
    // 5. MENU QUERY LINTAS NODE (REMOTE)
    // ==========================================

    public function indexQueryLintasNode()
    {
        try {
            // Menembak koneksi database pusat secara langsung dari halaman lokal Tebet
            $globalProducts = \DB::connection('mysql')->table('products')->get();
        } catch (\Exception $e) {
            $globalProducts = []; // Mengembalikan array kosong jika koneksi database pusat timeout
        }

        return view('tebet.query_lintas_node.index', compact('globalProducts'));
    }

    // ==========================================
    // 6. MENU INVENTARIS LOKAL
    // ==========================================

    public function indexInventaris()
    {
        // Mengambil data produk lokal tebet
        $products = \DB::connection('node_tebet')->table('products')->get();
        
        // Melakukan kalkulasi agregat langsung di level aplikasi
        $totalStok = $products->sum('stock'); // Menghitung total semua unit barang
        $produkKritis = $products->where('stock', '<', 5)->count(); // Menghitung berapa produk yang stoknya mau habis

        return view('tebet.inventaris.index', compact('products', 'totalStok', 'produkKritis'));
    }

    // ==========================================
    // 7. MENU REPLIKASI & KONSISTENSI DATA
    // ==========================================

    // Fungsi untuk menampilkan halaman utama Replikasi & Konsistensi
    public function indexReplikasis()
    {
        // Mengambil semua data transaksi yang ada di database lokal cabang Tebet
        $transactions = \DB::connection('node_tebet')->table('transactions')->get();

        // Merender halaman view replikasi yang ada di folder depan tebet
        return view('tebet.replikasi.index', compact('transactions'));
    }

    // Fungsi eksekusi untuk mengirim/mereplikasi data lokal ke database pusat (db_southmrt)
    public function prosesSync()
    {
        // 1. Ambil data transaksi dari node tebet yang statusnya masih 'Pending Sync'
        $pendingTransactions = \DB::connection('node_tebet')
            ->table('transactions')
            ->where('status', 'Pending Sync')
            ->get();

        // Validasi jika tidak ada data yang perlu disinkronkan
        if ($pendingTransactions->isEmpty()) {
            return redirect()->back()->with('error', 'Semua data transaksi di Node Tebet sudah sinkron dengan Server Pusat!');
        }

        // Memulai database transaction di kedua koneksi untuk menjaga konsistensi data terdistribusi
        \DB::connection('mysql')->beginTransaction();
        \DB::connection('node_tebet')->beginTransaction();

        try {
            foreach ($pendingTransactions as $trx) {
                // 2. Memasukkan (Replikasi) data transaksi lokal ke database pusat (db_southmrt)
                \DB::connection('mysql')->table('transactions')->insert([
                    'invoice'     => $trx->invoice,
                    'grand_total' => $trx->grand_total,
                    'status'      => 'Synced', // Di pusat otomatis tercatat dengan status Synced
                    'created_at'  => $trx->created_at,
                    'updated_at'  => $trx->updated_at,
                ]);

                // 3. Mengubah status transaksi di database lokal Tebet dari 'Pending Sync' menjadi 'Synced'
                \DB::connection('node_tebet')
                    ->table('transactions')
                    ->where('id', $trx->id)
                    ->update([
                        'status' => 'Synced',
                        'updated_at' => now()
                    ]);
            }

            // Jika semua proses looping berhasil tanpa kendala, commit kedua database secara permanen
            \DB::connection('mysql')->commit();
            \DB::connection('node_tebet')->commit();

            return redirect()->back()->with('success', 'Berhasil melakukan replikasi ' . $pendingTransactions->count() . ' data transaksi ke Server Pusat!');

        } catch (\Exception $e) {
            // Jika di tengah jalan ada error, batalkan semua perubahan di kedua database agar data tidak korup
            \DB::connection('mysql')->rollBack();
            \DB::connection('node_tebet')->rollBack();

            return redirect()->back()->with('error', 'Gagal memproses replikasi data: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 8. MENU LAPORAN ANALITIS
    // ==========================================

    public function indexLaporan()
    {
        // Mengambil riwayat transaksi dari database lokal tebet
        $transactions = \DB::connection('node_tebet')->table('transactions')->get();
        
        $totalOmset = $transactions->sum('grand_total'); // Menghitung akumulasi uang masuk
        $totalTransaksi = $transactions->count(); // Menghitung jumlah transaksi yang terjadi

        return view('tebet.laporan.index', compact('transactions', 'totalOmset', 'totalTransaksi'));
    }

    // ==========================================
    // 9. MENU DAFTAR CABANG NASIONAL
    // ==========================================
    public function indexCabang()
    {
        // Mencoba mengambil data daftar cabang dari server pusat, jika gagal akan memakai data fallback lokal
        try {
            $branches = \DB::connection('mysql')->table('branches')->get();
        } catch (\Exception $e) {
            // Data fallback jika koneksi pusat terputus agar aplikasi tidak crash
            $branches = collect([
                (object)['code' => 'BRC01', 'name' => 'SouthMart - Tebet', 'address' => 'Jl. Tebet Raya No. 10, Jakarta Selatan'],
                (object)['code' => 'BRC02', 'name' => 'SouthMart - Kemang', 'address' => 'Jl. Kemang Raya No. 45, Jakarta Selatan'],
                (object)['code' => 'BRC03', 'name' => 'SouthMart - Bogor', 'address' => 'Jl. Pajajaran No. 12, Bogor'],
            ]);
        }

        return view('tebet.cabang.index', compact('branches'));
    }

    // ==========================================
    // 10. MENU PENGGUNA / KARYAWAN LOKAL
    // ==========================================
    public function indexPengguna()
    {
        // Mengambil data pengguna/staf yang terdaftar di node lokal Tebet
        try {
            $users = \DB::connection('node_tebet')->table('users')->get();
        } catch (\Exception $e) {
            $users = collect([]); // Mengembalikan koleksi kosong jika tabel belum dibuat
        }

        return view('tebet.pengguna.index', compact('users'));
    }

    // ==========================================
    // 11. MENU PENGATURAN NODE LOKAL
    // ==========================================
    public function indexPengaturan()
    {
        // Mengambil informasi konfigurasi sistem dari file env secara dinamis
        $config = [
            'app_name' => config('app.name', 'SouthMart POS'),
            'env' => config('app.env', 'local'),
            'connection_default' => config('database.default', 'mysql'),
            'node_name' => 'Node Tebet (Retail Branch)',
        ];

        return view('tebet.pengaturan.index', compact('config'));
    }
}