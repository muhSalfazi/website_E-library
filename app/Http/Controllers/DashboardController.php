<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Menghitung jumlah anggota baru yang dibuat hari ini
        $newMembersCount = User::whereDate('created_at', today())->count();

        // Menghitung jumlah buku yang sedang dipinjam
        $borrowingBooksCount = Peminjaman::whereDate('created_at', today())->count();

        // Menghitung jumlah buku yang telah dikembalikan
        $returnBooksCount = Peminjaman::whereDate('tgl_kembali', today())->count();

        // Menghitung jumlah buku yang terlambat dikembalikan (denda)
        $overdueBooksCount = Peminjaman::where('created_at', '<', Carbon::now()->subDays(7))->count();

        // Mendapatkan ikhtisar 7 hari terakhir atau berdasarkan bulan yang dipilih
        $ikhtisarDays = $request->input('days', 7);  // default 7 hari jika tidak ada input
        $ikhtisar = Peminjaman::whereBetween('created_at', [now()->subDays($ikhtisarDays), now()])->get();

        // Total Pendapatan Denda
        $totalDenda = Denda::sum('uang_yg_dibyrkn');

        // Total Tunggakan
        $totalTunggakan = Denda::sum('denda_yg_diberikan');

        // Total Pendapatan Denda Tahun Lalu
        $lastYearTotalDenda = Denda::whereYear('created_at', now()->year - 1)->sum('uang_yg_dibyrkn');

        // Total Tunggakan Tahun Lalu
        $lastYearTotalTunggakan = Denda::whereYear('created_at', now()->year - 1)->sum('denda_yg_diberikan');

        return view('dashboard', compact(
            'newMembersCount',
            'borrowingBooksCount',
            'returnBooksCount',
            'overdueBooksCount',
            'ikhtisar',
            'totalDenda',
            'totalTunggakan',
            'lastYearTotalDenda',
            'lastYearTotalTunggakan',
            'ikhtisarDays'
        ));
    }
}