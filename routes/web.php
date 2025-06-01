<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\KonfigurasiLokasiController;
use App\Http\Controllers\Admin\PengajuanIzinController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// Route::middleware(['guest:karyawan'])->group(function(){
Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
// });

// Route::middleware(['guest:user'])->group(function(){
Route::get('/panel', function () {
    return view('auth.loginadmin');
})->name('loginadmin');
Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
// });

Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard',[DashboardController::class,'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //presensi
    Route::get('/presensi/create',[PresensiController::class,'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/updateprofile', [PresensiController::class, 'updateprofile']);

    //Histori
    Route::get('/presensi/histori',[PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
});

Route::middleware(['auth:user'])->prefix('panel')->name('admin.')->group(function(){
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('dashboard');
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin'])->name('logout');
    Route::get('/ganti-password', [ProfileController::class, 'showChangePasswordForm'])->name('admin.password.change');
    Route::post('/ganti-password', [ProfileController::class, 'changePassword'])->name('admin.password.update');

    // Karyawan Master Data: Direktur, HRD, Admin
    Route::middleware(['role:'.User::ROLE_DIREKTUR.','.User::ROLE_HRD.','.User::ROLE_ADMIN])->group(function () {
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/karyawan/{karyawan:nik}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/karyawan/{karyawan:nik}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{karyawan:nik}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    });

    // Monitoring Presensi: Direktur, Operasional Direktur
    Route::middleware(['role:'.User::ROLE_DIREKTUR.','.User::ROLE_OPERASIONAL_DIREKTUR])->group(function () {
        Route::get('/monitoring/presensi', [MonitoringController::class, 'index'])->name('monitoring.presensi');
    });

    // Laporan Presensi: Direktur, Operasional Direktur, HRD
    Route::middleware(['role:'.User::ROLE_DIREKTUR.','.User::ROLE_OPERASIONAL_DIREKTUR.','.User::ROLE_HRD])->group(function () {
        Route::get('/laporan/presensi', [LaporanController::class, 'index'])->name('laporan.presensi.index');
        Route::post('/laporan/presensi/cetak-karyawan', [LaporanController::class, 'cetakLaporanKaryawan'])->name('laporan.presensi.cetak_karyawan');
        Route::post('/laporan/presensi/cetak-rekap', [LaporanController::class, 'cetakRekapLaporan'])->name('laporan.presensi.cetak_rekap');
    });

    // Konfigurasi Lokasi Kantor: Direktur (Adjust if other roles need access)
    Route::middleware(['role:'.User::ROLE_DIREKTUR])->group(function () {
        Route::get('/konfigurasi/lokasi', [KonfigurasiLokasiController::class, 'index'])->name('konfigurasi.lokasi.index');
        Route::post('/konfigurasi/lokasi', [KonfigurasiLokasiController::class, 'storeOrUpdate'])->name('konfigurasi.lokasi.store');
    });

    // Persetujuan Izin/Sakit Karyawan: Direktur, Operasional Direktur, HRD, Admin
    Route::middleware(['role:'.User::ROLE_DIREKTUR.','.User::ROLE_OPERASIONAL_DIREKTUR.','.User::ROLE_HRD.','.User::ROLE_ADMIN])->group(function () {
        Route::get('/pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('pengajuan_izin.index');
        Route::post('/pengajuan-izin/{id}/update-status', [PengajuanIzinController::class, 'updateStatus'])->name('pengajuan_izin.update_status');
    });

});

