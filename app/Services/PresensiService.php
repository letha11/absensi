<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Presensi;
use App\Models\Karyawan; // Assuming Karyawan model is used for fetching user details
use App\Models\KonfigurasiLokasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Temporarily, to check existing presence. Will be replaced by Eloquent.
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config; // Added for accessing config

final class PresensiService
{
    /**
     * Calculate distance between two geographical points.
     *
     * @param float $latitude1 Latitude of point 1
     * @param float $longitude1 Longitude of point 1
     * @param float $latitude2 Latitude of point 2
     * @param float $longitude2 Longitude of point 2
     * @return array<string, float> Distance in meters
     */
    private function calculateDistance(float $latitude1, float $longitude1, float $latitude2, float $longitude2): array
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        // $feet = $miles * 5280;
        // $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    /**
     * Calculate points based on check-in time.
     *
     * @param Carbon $jamIn Actual check-in time
     * @return int Points awarded
     */
    private function calculatePoints(Carbon $jamIn): int
    {
        $defaultStartTimeString = Config::get('presensi.default_start_time', '07:00:00');
        $defaultStartTime = Carbon::createFromTimeString($defaultStartTimeString);

        // Before Time: 15 minutes or more before default_start_time
        if ($jamIn->lte($defaultStartTime->copy()->subMinutes(15))) {
            return 2;
        }

        // On-Time: Less than 15 minutes before or exactly at default_start_time
        if ($jamIn->gt($defaultStartTime->copy()->subMinutes(15)) && $jamIn->lte($defaultStartTime)) {
            return 1;
        }

        // Late: After default_start_time
        if ($jamIn->gt($defaultStartTime)) {
            return -1;
        }

        return 0; // Should not happen if logic is correct
    }

    /**
     * Store presence data (clock in/out).
     *
     * @param string $email Karyawan's email
     * @param string $lokasi User's current location "latitude,longitude"
     * @param string $imageBase64 Base64 encoded image string
     * @return array<string, mixed> Containing status, message, and type (in/out)
     */
    public function storePresensi(string $email, string $lokasi, string $imageBase64): array
    {
        $today = Carbon::now()->toDateString();
        $currentTime = Carbon::now(); // Use Carbon instance for easier comparison

        $konfigurasiLokasi = KonfigurasiLokasi::first();

        if (!$konfigurasiLokasi) {
            Log::error('Konfigurasi lokasi tidak ditemukan di database.');
            return [
                'status' => 'error',
                'message' => 'Konfigurasi lokasi kantor belum diatur. Silakan hubungi administrator.',
                'type' => null
            ];
        }

        $officeLatitude = (float) $konfigurasiLokasi->latitude;
        $officeLongitude = (float) $konfigurasiLokasi->longitude;
        $radiusConfig = (int) $konfigurasiLokasi->radius;

        [$userLatitude, $userLongitude] = array_map('floatval', explode(",", $lokasi));

        $distanceData = $this->calculateDistance($officeLatitude, $officeLongitude, $userLatitude, $userLongitude);
        $jarakMeter = round($distanceData["meters"]);

        if ($jarakMeter > $radiusConfig) {
            return [
                'status' => 'error',
                'message' => "Maaf Anda Berada di Luar Radius Kantor, Jarak Anda {$jarakMeter} Meter dari Kantor",
                'type' => null
            ];
        }

        // Check existing presensi for today using Eloquent
        $existingPresensi = Presensi::where('karyawan_email', $email)
                                    ->where('tgl_presensi', $today)
                                    ->first();

        $ket = $existingPresensi ? 'out' : 'in';

        // We need Karyawan->nik for the filename, fetch the Karyawan model first
        $karyawan = Karyawan::where('email', $email)->first();
        if (!$karyawan) {
            // This should ideally not happen if $email comes from an authenticated user
            return [
                'status' => 'error',
                'message' => 'Karyawan tidak ditemukan.',
                'type' => null
            ];
        }
        $nikForFilename = $karyawan->nik; // Use NIK for filename as per current convention

        $folderPathOnPublicDisk = "uploads/absensi/"; // Path relative to storage/app/public
        $formatName = "{$nikForFilename}-{$today}-{$ket}";
        $fileName = "{$formatName}.png";
        $filePathOnPublicDisk = $folderPathOnPublicDisk . $fileName;

        // Decode and store image more robustly
        $pureBase64 = $imageBase64;
        if (strpos($pureBase64, ';base64,') !== false) {
            [, $pureBase64] = explode(';base64,', $pureBase64, 2);
        }

        $decodedImage = base64_decode($pureBase64, true);

        if ($decodedImage === false) {
            return [
                'status' => 'error',
                'message' => 'Invalid image data provided.',
                'type' => $ket
            ];
        }

        if ($existingPresensi) { // Clock Out
            if ($existingPresensi->jam_out !== null) {
                 return [
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan absen pulang hari ini.',
                    'type' => 'out'
                ];
            }
            $existingPresensi->jam_out = $currentTime->toTimeString(); // Store as string H:i:s
            $existingPresensi->foto_out = $fileName; // Storing only filename
            $existingPresensi->lokasi_out = $lokasi;

            if ($existingPresensi->save()) {
                Storage::disk('public')->put($filePathOnPublicDisk, $decodedImage);
                return [
                    'status' => 'success',
                    'message' => 'Terima kasih atas kerja keras anda. Hati-hati di Jalan',
                    'type' => 'out'
                ];
            }
        } else { // Clock In
            $points = $this->calculatePoints($currentTime); // Calculate points

            $newPresensi = Presensi::create([
                'karyawan_email' => $email,
                'tgl_presensi' => $today,
                'jam_in' => $currentTime->toTimeString(), // Store as string H:i:s
                'foto_in' => $fileName, // Storing only filename
                'lokasi_in' => $lokasi,
                'point' => $points, // Store points
            ]);

            if ($newPresensi) {
                 Storage::disk('public')->put($filePathOnPublicDisk, $decodedImage);
                return [
                    'status' => 'success',
                    'message' => 'Terima kasih. Selamat Bekerja',
                    'type' => 'in'
                ];
            }
        }

        return [
            'status' => 'error',
            'message' => 'Absensi Gagal. Silahkan Hubungi Icik Bos',
            'type' => $ket
        ];
    }
}
