<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

final class DashboardController extends Controller
{
    public function index(): View
    {
        $hariini = Carbon::today()->toDateString();
        $bulanini = Carbon::today()->month;
        $tahunini = Carbon::today()->year;
        
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();

        $presensihariini = $karyawan->presensi()
            ->where('tgl_presensi', $hariini)
            ->first();

        $historibulanini = $karyawan->presensi()
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        $rekappresensi = $karyawan->presensi()
            ->selectRaw('COUNT(karyawan_email) as jmlhadir, SUM(IF(jam_in > "07:00", 1,0)) as jmlterlambat')
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->first();

        $leaderboard = Presensi::with('karyawan')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();
            
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        $rekapizin = $karyawan->pengajuanIzin()
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('status_approved', 'a')
            ->whereMonth('tgl_izin', $bulanini)
            ->whereYear('tgl_izin', $tahunini)
            ->first();

        return view ('dashboard.dashboard', compact(
            'presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 
            'rekappresensi', 'leaderboard', 'rekapizin'
        ));
    }

    public function dashboardadmin(): View
    {
        $hariini = Carbon::today()->toDateString();
        
        $rekappresensi = Presensi::query()
            ->selectRaw('COUNT(karyawan_email) as jmlhadir, SUM(IF(jam_in > "07:00", 1,0)) as jmlterlambat')
            ->where('tgl_presensi', $hariini)
            ->first();

        $rekapizin = PengajuanIzin::query()
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('tgl_izin', $hariini)
            ->where('status_approved',1)
            ->first();
            
        return view('dashboard.dashboardadmin',  compact('rekappresensi', 'rekapizin'));
    }
}
