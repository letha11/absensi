<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

final class PengajuanIzinController extends Controller
{
    public function index(Request $request): View
    {
        $query = PengajuanIzin::with('karyawan')->orderBy('created_at', 'desc');

        // Add filtering by status if needed, e.g., only show pending requests by default
        if ($request->has('status_approved') && $request->status_approved !== 'all') {
            $query->where('status_approved', $request->status_approved);
        } else {
            // Default to showing pending requests if no filter or 'all' is not selected
            $query->where('status_approved', 'p'); 
        }

        $pengajuanIzin = $query->paginate(15);

        return view('admin.pengajuan_izin.index', compact('pengajuanIzin'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'status_approved' => 'required|in:a,d', // a for approved, d for declined
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $pengajuan = PengajuanIzin::findOrFail($id);
        $pengajuan->status_approved = $request->input('status_approved');
        $pengajuan->save();

        $message = $request->input('status_approved') === 'a' ? 'Pengajuan izin berhasil disetujui.' : 'Pengajuan izin berhasil ditolak.';

        return Redirect::route('admin.pengajuan_izin.index')->with('success', $message);
    }
}
