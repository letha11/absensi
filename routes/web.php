<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\Admin\KaryawanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:karyawan'])->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

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

Route::middleware(['auth:user'])->group(function(){
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    // Karyawan Master Data
    Route::get('/panel/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan.index');
    Route::get('/panel/karyawan/create', [KaryawanController::class, 'create'])->name('admin.karyawan.create');
    Route::post('/panel/karyawan', [KaryawanController::class, 'store'])->name('admin.karyawan.store');
    Route::get('/panel/karyawan/{karyawan:nik}/edit', [KaryawanController::class, 'edit'])->name('admin.karyawan.edit');
    Route::put('/panel/karyawan/{karyawan:nik}', [KaryawanController::class, 'update'])->name('admin.karyawan.update');
    Route::delete('/panel/karyawan/{karyawan:nik}', [KaryawanController::class, 'destroy'])->name('admin.karyawan.destroy');
});

