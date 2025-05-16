<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

final class MonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $requestedDate = $request->input('tanggal');
        $selectedDate = $requestedDate ? Carbon::parse($requestedDate) : Carbon::today();
        // Keep a 'today' variable for general display, which could be the selected date or actual today for clarity
        $displayDate = $selectedDate->isToday() ? Carbon::today() : $selectedDate->copy();

        $defaultStartTime = Carbon::createFromTimeString(Config::get('presensi.default_start_time', '07:00:00'));

        $presensiHariIni = Presensi::with('karyawan')
            ->where('tgl_presensi', $selectedDate->toDateString())
            ->orderBy('jam_in', 'asc') // Order by clock-in time
            ->get()
            ->map(function ($item) use ($defaultStartTime) {
                if ($item->jam_in) {
                    // Assuming $item->jam_in is a Carbon object from model cast
                    $item->is_late = $item->jam_in->gt($defaultStartTime);
                    $item->jam_in_formatted = $item->jam_in->format('H:i:s');
                    
                    if ($item->jam_out) {
                        // Assuming $item->jam_out is a Carbon object from model cast
                        $item->jam_out_formatted = $item->jam_out->format('H:i:s');
                    } else {
                        $item->jam_out_formatted = '-';
                    }
                } else {
                    $item->is_late = false;
                    $item->jam_in_formatted = '-';
                    $item->jam_out_formatted = '-';
                }
                 // Parse lokasi_in for the map
                if ($item->lokasi_in) {
                    $lokasiParts = explode(',', $item->lokasi_in);
                    if (count($lokasiParts) === 2) {
                        $item->latitude = trim($lokasiParts[0]);
                        $item->longitude = trim($lokasiParts[1]);
                    }
                }
                return $item;
            });

        $jumlahTerlambat = $presensiHariIni->where('is_late', true)->count();
        $jumlahHadir = $presensiHariIni->count();

        $officeLatitude = Config::get('presensi.office_latitude');
        $officeLongitude = Config::get('presensi.office_longitude');
        $officeRadius = Config::get('presensi.radius_meters');

        return view('admin.monitoring.index', compact(
            'presensiHariIni',
            'jumlahTerlambat',
            'jumlahHadir',
            'officeLatitude',
            'officeLongitude',
            'officeRadius',
            'displayDate',
            'selectedDate'
        ));
    }
}
