<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan; // Assuming Karyawan model exists
use Illuminate\Http\Request; // Keep for future use (store, update)
use Illuminate\View\View;
use App\Http\Requests\Admin\StoreKaryawanRequest; // Import FormRequest
use Illuminate\Support\Facades\Hash; // For password hashing
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage; // For file uploads
use App\Http\Requests\Admin\UpdateKaryawanRequest; // Import FormRequest

final class KaryawanController extends Controller
{
    private const KARYAWAN_PER_PAGE = 15; // Or any number you prefer

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Karyawan::query();

        if ($request->has('search') && $request->input('search') !== null) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nik', 'like', $searchTerm)
                  ->orWhere('nama_lengkap', 'like', $searchTerm);
            });
        }

        $karyawans = $query->orderBy('created_at', 'desc')->paginate(self::KARYAWAN_PER_PAGE);
        
        // Pass the search term back to the view to populate the search input
        $searchTerm = $request->input('search');

        return view('admin.karyawan.index', compact('karyawans', 'searchTerm'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKaryawanRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $karyawan = new Karyawan();
            $karyawan->nik = $validatedData['nik'];
            $karyawan->nama_lengkap = $validatedData['nama_lengkap'];
            $karyawan->jabatan = $validatedData['jabatan'];
            $karyawan->no_hp = $validatedData['no_hp'];
            $karyawan->email = $validatedData['email'];
            $karyawan->password = Hash::make($validatedData['password']);

            if ($request->hasFile('foto')) {
                $photoFile = $request->file('foto');
                $directory = 'uploads/karyawan'; // Path relative to the 'public' disk's root
                // Use NIK for the filename to ensure uniqueness and predictability
                $photoName = $validatedData['nik'] . '.' . $photoFile->getClientOriginalExtension();
                
                // Store the new photo on the 'public' disk
                $storedPath = $photoFile->storeAs($directory, $photoName, 'public');

                if ($storedPath) {
                    $karyawan->foto = $photoName; // Store just the filename
                } else {
                    // Handle storage failure if necessary, e.g., log error, return with error message
                    // For now, we'll let it proceed, and 'foto' might remain null or unchanged
                }
            }

            $karyawan->save();

            return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Log the error for debugging: Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data karyawan. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan): View
    {
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKaryawanRequest $request, Karyawan $karyawan): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $updatePayload = [
                'nama_lengkap' => $validatedData['nama_lengkap'],
                'jabatan' => $validatedData['jabatan'],
                'no_hp' => $validatedData['no_hp'],
                'email' => $validatedData['email'],
            ];

            if (!empty($validatedData['password'])) {
                $updatePayload['password'] = Hash::make($validatedData['password']);
            }

            if ($request->hasFile('foto')) {
                $photoFile = $request->file('foto');
                $directory = 'uploads/karyawan'; // Path relative to the 'public' disk's root
                // Use Karyawan's NIK for the filename
                $photoName = $karyawan->nik . '.' . $photoFile->getClientOriginalExtension();

                // Delete old photo if it exists and the new name is different or if the old one is simply being replaced
                if ($karyawan->foto) {
                    $oldPhotoPath = $directory . '/' . $karyawan->foto;
                    if (Storage::disk('public')->exists($oldPhotoPath) && $karyawan->foto !== $photoName) {
                        Storage::disk('public')->delete($oldPhotoPath);
                    } else if (Storage::disk('public')->exists($oldPhotoPath) && $karyawan->foto === $photoName && $photoFile->getClientOriginalName() !== $karyawan->foto) {
                         // Case: new file uploaded with same NIK.extension but is a different actual file (e.g. user re-uploads)
                         // Or simply, if a new file is uploaded, delete the old one if it has the same name
                         Storage::disk('public')->delete($oldPhotoPath);
                    }
                }
                
                // Store the new photo on the 'public' disk
                $storedPath = $photoFile->storeAs($directory, $photoName, 'public');

                if ($storedPath) {
                    $updatePayload['foto'] = $photoName; // Store just the filename
                } else {
                    // Handle storage failure
                }
            }

            $karyawan->update($updatePayload);

            return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diupdate.');
        } catch (\Exception $e) {
            // Log the error: Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate data karyawan. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        try {
            // Delete photo if exists
            if ($karyawan->foto) {
                Storage::delete('public/uploads/karyawan/' . $karyawan->foto);
            }
            $karyawan->delete();
            return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            // Log the error: Log::error($e->getMessage());
            return redirect()->route('admin.karyawan.index')->with('error', 'Gagal menghapus data karyawan.');
        }
    }
} 