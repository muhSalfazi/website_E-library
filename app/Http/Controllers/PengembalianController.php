<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PengembalianController extends Controller
{
    public function index()
    {
        // Ambil semua data pengembalian dengan return_date terisi
        $pengembalians = Peminjaman::whereNotNull('return_date')->get();

        // Kirim data pengembalian ke tampilan 'pengembalian'
        return view('Pengembalian.daftarpengembalian', ['pengembalians' => $pengembalians]);
    }

    public function search()
    {
        return view('Pengembalian.searchPengembalian');
    }

    public function cari(Request $request)
    {
        $request->validate([
            'resi_pnjmn' => 'required', // Tambahkan aturan validasi yang sesuai
        ]);

        $resi = $request->input('resi_pnjmn');

        // Cari peminjaman berdasarkan nomor resi
        $peminjaman = Peminjaman::where('resi_pjmn', $resi)->first();

        if ($peminjaman) {
            return view('Pengembalian.searchPengembalian', ['peminjaman' => $peminjaman]);
        } else {
            $errors = ['Resi peminjaman tidak ditemukan.'];
            return redirect()->route('pengembalian.search')->withErrors($errors);
        }
    }

    public function simpan(Request $request, Peminjaman $peminjaman = null)
    {
        $request->validate([
            'resi_pjmn' => 'sometimes|required|string|exists:tbl_peminjaman,resi_pjmn',
            'id' => 'sometimes|required|integer|exists:tbl_peminjaman,id',
        ]);

        if ($request->has('id')) {
            $peminjaman = Peminjaman::findOrFail($request->input('id'));
        } else if ($request->has('resi_pjmn')) {
            $peminjaman = Peminjaman::where('resi_pjmn', $request->input('resi_pjmn'))->firstOrFail();
        }

        // Update return_date dengan waktu saat ini
        $peminjaman->return_date = Carbon::now();
        $peminjaman->save();

        // Redirect ke halaman daftarpengembalian dengan pesan sukses
        return redirect()->route('pengembalian')->with('success', 'Tanggal pengembalian berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $pengembalian = Peminjaman::findOrFail($id);
        $pengembalian->delete();

        // Redirect ke halaman daftarpengembalian dengan pesan sukses
        return redirect()->route('pengembalian')->with('success', 'Peminjaman berhasil dihapus.');
    }
}