<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Digunakan untuk menjalankan query database manual terdistribusi

class PosController extends Controller
{
    // Fungsi untuk menampilkan halaman utama POS Kasir Tebet
    public function index()
    {
        // Mengambil data produk langsung dari database lokal Tebet menggunakan koneksi 'node_tebet'
        $products = DB::connection('node_tebet')->table('products')->get();

        // Mengirim data produk tersebut ke halaman view tebet.pos.index yang kemarin kita buat
        return view('tebet.pos.index', compact('products'));
    }

    // Fungsi untuk memproses transaksi penjualan (Checkout) di kasir Tebet
    public function checkout(Request $request)
    {
        // Menangkap nominal total belanja yang dikirim dari form halaman web
        $grandTotal = $request->input('grand_total');

        // Menyimpan data transaksi ke dalam tabel 'transactions' di database lokal db_southmart_tebet
        DB::connection('node_tebet')->table('transactions')->insert([
            'invoice' => 'INV-TBT-' . time(), // Membuat nomor invoice unik otomatis berdasarkan waktu
            'grand_total' => $grandTotal, // Menyimpan total uang belanjaan
            'status' => 'Pending Sync', // Status SBDT: menandakan data ini di-fragmentasi lokal dan belum ditarik pusat
            'created_at' => now(), // Mencatat waktu tanggal transaksi dibuat
            'updated_at' => now(), // Mencatat waktu tanggal transaksi diperbarui
        ]);

        // Setelah berhasil disimpan, kembalikan halaman kasir dengan memunculkan notifikasi sukses hijau
        return redirect()->route('tebet.pos.index')->with('success', 'Transaksi Cabang Tebet Berhasil Disimpan di db_southmart_tebet!');
    }
}