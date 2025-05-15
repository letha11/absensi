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
     * @param string $email The user's email
     * @param array<string, mixed> $data Validated data (nama_lengkap, no_hp, password (optional))
     * @param ?UploadedFile $photoFile Optional new photo file
     * @return bool True on success, false on failure
     */
    public function updateUserProfile(string $email, array $data, ?UploadedFile $photoFile): bool
    {
        $karyawan = Karyawan::find($email); // Find by email (new primary key)
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
            $directory = 'uploads/karyawan'; // Path relative to the 'public' disk's root (storage/app/public)
            $photoName = $karyawan->nik . '.' . $photoFile->getClientOriginalExtension();
            
            // If there's an old photo, prepare its path for deletion
            if ($karyawan->foto) {
                $oldPhotoPath = $directory . '/' . $karyawan->foto;
                // Check if the old photo exists on the public disk and is different from the new one before deleting
                if (Storage::disk('public')->exists($oldPhotoPath) && $karyawan->foto !== $photoName) {
                    Storage::disk('public')->delete($oldPhotoPath);
                }
            }
            
            // Store the new photo on the 'public' disk.
            // The storeAs method on UploadedFile returns the path relative to the disk's root if successful, false otherwise.
            $storedPath = $photoFile->storeAs($directory, $photoName, 'public');

            if ($storedPath) { // Ensure storing was successful
                $updateData['foto'] = $photoName; // photoName is just the filename, which is what we store in DB
            } else {
                // Optional: Log an error or return false if file storage is critical and failed.
                // If $storedPath is false, 'foto' won't be updated in the database.
            }
        }

        return $karyawan->update($updateData);
    }
} 