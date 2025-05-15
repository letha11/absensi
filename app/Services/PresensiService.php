<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Presensi;
use App\Models\Karyawan; // Assuming Karyawan model is used for fetching user details
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Temporarily, to check existing presence. Will be replaced by Eloquent.
use Carbon\Carbon;

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
     * Store presence data (clock in/out).
     *
     * @param string $nik
     * @param string $lokasi User's current location "latitude,longitude"
     * @param string $imageBase64 Base64 encoded image string
     * @return array<string, mixed> Containing status, message, and type (in/out)
     */
    public function storePresensi(string $nik, string $lokasi, string $imageBase64): array
    {
        $today = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $officeLatitude = (float) config('presensi.office_latitude');
        $officeLongitude = (float) config('presensi.office_longitude');
        $radiusConfig = (int) config('presensi.radius_meters');

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
        $existingPresensi = Presensi::where('nik', $nik)
                                    ->where('tgl_presensi', $today)
                                    ->first();
        
        $ket = $existingPresensi ? 'out' : 'in';

        $folderPath = "public/uploads/absensi/"; // Relative to Storage::disk('local')->put()
        $formatName = "{$nik}-{$today}-{$ket}";
        $fileName = "{$formatName}.png";
        $filePath = $folderPath . $fileName;

        // Decode and store image
        // The $imageBase64 is expected to be just the data part, without "data:image/png;base64,"
        $imageParts = explode(";base64,", $imageBase64);
        $decodedImage = base64_decode($imageParts[1] ?? $imageBase64); // Handle if prefix is not there

        if ($existingPresensi) { // Clock Out
            if ($existingPresensi->jam_out !== null) {
                 return [
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan absen pulang hari ini.',
                    'type' => 'out'
                ];
            }
            $existingPresensi->jam_out = $currentTime;
            $existingPresensi->foto_out = $fileName; // Storing only filename
            $existingPresensi->lokasi_out = $lokasi;
            
            if ($existingPresensi->save()) {
                Storage::put($filePath, $decodedImage);
                return [
                    'status' => 'success',
                    'message' => 'Terima kasih atas kerja keras anda. Hati-hati di Jalan',
                    'type' => 'out'
                ];
            }
        } else { // Clock In
            $newPresensi = Presensi::create([
                'nik' => $nik,
                'tgl_presensi' => $today,
                'jam_in' => $currentTime,
                'foto_in' => $fileName, // Storing only filename
                'lokasi_in' => $lokasi,
            ]);

            if ($newPresensi) {
                Storage::put($filePath, $decodedImage);
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