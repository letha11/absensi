<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

final class UserProfileService
{
    /**
     * Update user profile.
     *
     * @param string $nik
     * @param array<string, mixed> $data Validated data (nama_lengkap, no_hp, password (optional))
     * @param ?UploadedFile $photoFile Optional new photo file
     * @return bool True on success, false on failure
     */
    public function updateUserProfile(string $nik, array $data, ?UploadedFile $photoFile): bool
    {
        $karyawan = Karyawan::find($nik);
        if (!$karyawan) {
            return false;
        }

        $updateData = [
            'nama_lengkap' => $data['nama_lengkap'],
            'no_hp' => $data['no_hp'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if ($photoFile) {
            $folderPath = 'public/uploads/karyawan'; // Relative to storage disk
            // Use a more unique name or keep the nik.extension format if preferred
            $photoName = $nik . '.' . $photoFile->getClientOriginalExtension();
            // Deleting old photo first if it exists and has a different name or to prevent orphaned files
            if ($karyawan->foto && Storage::exists($folderPath . '/' . $karyawan->foto)) {
                 if ($karyawan->foto !== $photoName) { // Only delete if name changes or if you always want to replace
                    Storage::delete($folderPath . '/' . $karyawan->foto);
                 }
            }
            $photoFile->storeAs($folderPath, $photoName); // Uses default disk (usually 'local')
            $updateData['foto'] = $photoName;
        } else {
            // If no new photo is uploaded, but password is empty, 
            // ensure 'foto' is not accidentally removed from $updateData if it wasn't in $data
            // However, the current logic in controller keeps $karyawan->foto if no new file.
            // This service assumes $data will not contain 'foto' if no file is uploaded.
        }

        return $karyawan->update($updateData);
    }
} 