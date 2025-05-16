<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreIzinRequest;
use App\Http\Requests\StorePresensiRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Karyawan;
use App\Models\KonfigurasiLokasi;
use App\Services\IzinService;
use App\Services\PresensiService;
use App\Services\UserProfileService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Keep for histori, for now
use Illuminate\Support\Facades\Redirect; // Keep for Redirect::back()
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

final class PresensiController extends Controller
{
    public function __construct(
        private readonly PresensiService $presensiService,
        private readonly UserProfileService $userProfileService,
        private readonly IzinService $izinService
    ) {
    }

    public function create(): View|RedirectResponse
    {
        $today = Carbon::today()->toDateString();
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();
        
        $cekPresensi = $karyawan->presensi()
            ->where('tgl_presensi', $today)
            ->count();

        $konfigurasiLokasi = KonfigurasiLokasi::first();

        if (!$konfigurasiLokasi) {
            Log::error('Konfigurasi lokasi tidak ditemukan di database untuk halaman presensi create.');
            return Redirect::back()->with('error', 'Konfigurasi lokasi kantor belum diatur. Tidak dapat menampilkan halaman presensi.');
        }

        $officeLatitude = (float) $konfigurasiLokasi->latitude;
        $officeLongitude = (float) $konfigurasiLokasi->longitude;
        $radiusMeters = (int) $konfigurasiLokasi->radius;
        
        return view('presensi.create', [
            'cek' => $cekPresensi,
            'officeLatitude' => $officeLatitude,
            'officeLongitude' => $officeLongitude,
            'radiusMeters' => $radiusMeters
        ]);
    }

    public function store(StorePresensiRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();
        $identifier = (string) $karyawan->email;

        $result = $this->presensiService->storePresensi(
            $identifier,
            $validatedData['lokasi'],
            $validatedData['image']
        );

        if ($result['status'] === 'success') {
            return response()->json($result);
        }

        return response()->json($result, 422); // Unprocessable Entity for known errors like out of radius
    }

    public function editprofile(): View
    {
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var \App\Models\Karyawan $karyawanAuth */
        $karyawanAuth = Auth::guard('karyawan')->user();
        $email = (string) $karyawanAuth->email;
        
        $profileData = $request->safe()->except(['foto', 'password_confirmation']);
        $photoFile = $request->hasFile('foto') ? $request->file('foto') : null;

        $success = $this->userProfileService->updateUserProfile($email, $profileData, $photoFile);

        if ($success) {
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        }

        return Redirect::back()->with(['error' => 'Data Gagal di Update']);
    }

    public function histori(): View
    {
        $namabulan = [
            "", "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request): View
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();

        if (!$bulan || !$tahun) {
            return view('presensi.gethistori', ['histori' => collect()]);
        }
        
        $histori = $karyawan->presensi()
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }
    
    public function izin(): View
    {
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();
        $dataizin = $karyawan->pengajuanIzin()->orderByDesc('tgl_izin')->get();

        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin(): View
    {
        return view('presensi.buatizin');
    }

    public function storeizin(StoreIzinRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::guard('karyawan')->user();
        $identifier = (string) $karyawan->email;

        $pengajuanIzin = $this->izinService->storeIzin(
            $identifier,
            $validatedData['tgl_izin'],
            $validatedData['status'],
            $validatedData['keterangan']
        );

        if ($pengajuanIzin) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Tersimpan']);
        }

        return redirect()->back()->withInput()->with(['error' => 'Data Gagal Disimpan']);
    }
}
