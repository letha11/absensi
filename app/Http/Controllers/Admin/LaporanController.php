<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // For complex queries if needed
use Illuminate\View\View;
use Carbon\Carbon;

final class LaporanController extends Controller
{
    /**
     * Display the report selection page.
     */
    public function index(): View
    {
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        return view('admin.laporan.index', compact('karyawans'));
    }

    /**
     * Generate and display an individual employee presence report.
     */
    public function cetakLaporanKaryawan(Request $request): View
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|digits:4',
            'karyawan_email' => 'required|email|exists:karyawan,email',
        ]);

        $bulan = (int) $request->input('bulan');
        $tahun = (int) $request->input('tahun');
        $karyawanEmail = $request->input('karyawan_email');

        $karyawan = Karyawan::where('email', $karyawanEmail)->firstOrFail();
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');
        $defaultStartTime = Carbon::createFromTimeString(config('presensi.default_start_time', '07:00:00'));

        $laporanPresensi = Presensi::where('karyawan_email', $karyawanEmail)
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->orderBy('tgl_presensi', 'asc')
            ->get()
            ->map(function ($item) use ($defaultStartTime) {
                if ($item->jam_in) {
                    $jamIn = $item->jam_in;
                    $item->is_late = $jamIn->gt($defaultStartTime);
                    $item->jam_in_formatted = $jamIn->format('H:i:s');
                    if ($item->jam_out) {
                        $item->jam_out_formatted = $item->jam_out->format('H:i:s');
                    }
                } else {
                    $item->is_late = false;
                    $item->jam_in_formatted = '-';
                }
                $item->tanggal_formatted = Carbon::parse($item->tgl_presensi)->translatedFormat('d F Y');
                return $item;
            });
        
        $totalHadir = $laporanPresensi->count();
        $totalTerlambat = $laporanPresensi->where('is_late', true)->count();

        return view('admin.laporan.cetak_karyawan', compact(
            'karyawan',
            'laporanPresensi',
            'bulan',
            'tahun',
            'namaBulan',
            'totalHadir',
            'totalTerlambat'
        ));
    }

    /**
     * Generate and display a recap presence report for all employees.
     */
    public function cetakRekapLaporan(Request $request): View
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|digits:4',
        ]);

        $bulan = (int) $request->input('bulan');
        $tahun = (int) $request->input('tahun');
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');
        $defaultStartTimeString = config('presensi.default_start_time', '07:00:00');

        // Fetch all employees
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        $rekapData = [];

        foreach ($karyawans as $karyawan) {
            $presensiBulanan = Presensi::where('karyawan_email', $karyawan->email)
                ->whereMonth('tgl_presensi', $bulan)
                ->whereYear('tgl_presensi', $tahun)
                ->orderBy('tgl_presensi', 'asc')
                ->get();

            $totalHadir = $presensiBulanan->count();
            $totalTerlambat = $presensiBulanan->filter(function ($p) use ($defaultStartTimeString) {
                return $p->jam_in && $p->jam_in->gt(Carbon::createFromTimeString($defaultStartTimeString));
            })->count();
            
            // You might want to get total leave, sick days if that data is available and needed for rekap
            // For now, just presence and lateness

            $rekapData[] = [
                'karyawan' => $karyawan,
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                // Add other aggregates if needed, e.g., total_izin, total_sakit
            ];
        }

        return view('admin.laporan.cetak_rekap', compact(
            'rekapData',
            'bulan',
            'tahun',
            'namaBulan'
        ));
    }
}
