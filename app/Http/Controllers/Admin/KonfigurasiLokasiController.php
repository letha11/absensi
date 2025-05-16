<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

final class KonfigurasiLokasiController extends Controller
{
    public function index(): View
    {
        $konfigurasi = KonfigurasiLokasi::first();
        return view('admin.konfigurasi.lokasi', compact('konfigurasi'));
    }

    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_kantor' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        KonfigurasiLokasi::updateOrCreate(
            ['id' => 1], // Assuming there is only one configuration record, or use firstOrNew()
            [
                'lokasi_kantor' => $request->input('lokasi_kantor'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'radius' => $request->input('radius'),
            ]
        );

        return Redirect::route('admin.konfigurasi.lokasi.index')->with('success', 'Konfigurasi lokasi berhasil disimpan.');
    }
}
