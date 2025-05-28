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
use Carbon\CarbonPeriod;

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
            ->orderBy('jam_in', 'asc')
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

    public function dashboardadmin(Request $request): View
    {
        $hariini = Carbon::today()->toDateString();
        
        $rekappresensi = Presensi::query()
            ->selectRaw('COUNT(DISTINCT karyawan_email) as jmlhadir, SUM(IF(jam_in > "07:00", 1,0)) as jmlterlambat')
            ->where('tgl_presensi', $hariini)
            ->first();

        $rekapizin = PengajuanIzin::query()
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('tgl_izin', $hariini)
            ->where('status_approved', 'a')
            ->first();

        // Point Chart Data
        $selectedYear = (int) $request->input('year', Carbon::now()->year);
        $selectedMonth = (int) $request->input('month', Carbon::now()->month);

        $daysInPeriod = CarbonPeriod::create(Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth(), Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth());
        
        $chartLabels = [];
        $dailyTotalPoints = [];

        foreach ($daysInPeriod as $date) {
            $chartLabels[] = $date->format('d');
            $totalPointsForDay = Presensi::whereYear('tgl_presensi', $selectedYear)
                                        ->whereMonth('tgl_presensi', $selectedMonth)
                                        ->whereDay('tgl_presensi', $date->day)
                                        ->sum('point');
            $dailyTotalPoints[] = (int) $totalPointsForDay;
        }

        // Determine min and max for y-axis scaling with padding
        $minDataValue = !empty($dailyTotalPoints) ? min($dailyTotalPoints) : 0;
        $maxDataValue = !empty($dailyTotalPoints) ? max($dailyTotalPoints) : 0;
        $currentAbsoluteMax = max(abs($minDataValue), abs($maxDataValue));

        if ($currentAbsoluteMax == 0) {
            $absoluteMaxDeviation = 2; // Default for all zero data, e.g., scale from -2 to 2 for better visibility
        } else {
            // Add padding (e.g., 10% of the max deviation, or at least 1 unit)
            $padding = max(1, (int)ceil($currentAbsoluteMax * 0.1));
            $absoluteMaxDeviation = $currentAbsoluteMax + $padding;
        }

        $yAxisMin = -$absoluteMaxDeviation;
        $yAxisMax = $absoluteMaxDeviation;

        $chartDatasets = [
            [
                'label' => 'Total Poin Harian',
                'data' => $dailyTotalPoints,
                'backgroundColor' => 'rgba(54, 162, 235, 0.6)', // A nice blue for bar chart
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            ]
        ];

        // Leaderboard Data Calculation
        $karyawansForLeaderboard = Karyawan::orderBy('nama_lengkap')->get();
        $leaderboardData = [];
        foreach ($karyawansForLeaderboard as $karyawan) {
            $totalPointsKaryawan = Presensi::where('karyawan_email', $karyawan->email)
                ->whereYear('tgl_presensi', $selectedYear)
                ->whereMonth('tgl_presensi', $selectedMonth)
                ->sum('point');
            
            $leaderboardData[] = [
                'nama_lengkap' => $karyawan->nama_lengkap,
                'nik' => $karyawan->nik, // Assuming NIK might be useful
                'jabatan' => $karyawan->jabatan, // Assuming Jabatan might be useful
                'total_points' => (int) $totalPointsKaryawan
            ];
        }
        // Sort leaderboard data by total_points descending
        usort($leaderboardData, function ($a, $b) {
            return $b['total_points'] <=> $a['total_points'];
        });
        
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $availableYears = range(Carbon::now()->year - 5, Carbon::now()->year + 1);

        return view('dashboard.dashboardadmin',  compact(
            'rekappresensi', 
            'rekapizin',
            'chartLabels',
            'chartDatasets',
            'selectedYear',
            'selectedMonth',
            'namaBulan',
            'availableYears',
            'yAxisMin',
            'yAxisMax',
            'leaderboardData' // Pass leaderboard data to view
        ));
    }
}
