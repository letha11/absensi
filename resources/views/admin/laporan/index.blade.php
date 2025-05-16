@extends('layouts.admin.tabler')

@section('title', 'Laporan Presensi')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Laporan Presensi Karyawan
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Laporan Presensi per Karyawan</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any() && old('report_type') === 'karyawan')
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.laporan.presensi.cetak_karyawan') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="report_type" value="karyawan">
                            <div class="mb-3">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" class="form-select" required>
                                    <option value="">-- Pilih Bulan --</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" class="form-select" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                                        <option value="{{ $tahun }}" {{ old('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Karyawan</label>
                                <select name="karyawan_email" class="form-select" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->email }}" {{ old('karyawan_email') == $karyawan->email ? 'selected' : '' }}>{{ $karyawan->nama_lengkap }} ({{ $karyawan->nik }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                    Cetak Laporan Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rekap Laporan Presensi Seluruh Karyawan</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any() && old('report_type') === 'rekap')
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.laporan.presensi.cetak_rekap') }}" method="POST" target="_blank">
                            @csrf
                             <input type="hidden" name="report_type" value="rekap">
                            <div class="mb-3">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" class="form-select" required>
                                    <option value="">-- Pilih Bulan --</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" class="form-select" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                                        <option value="{{ $tahun }}" {{ old('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-success w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-analytics" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>
                                    Cetak Rekap Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 