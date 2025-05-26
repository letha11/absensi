@extends('layouts.admin.tabler') {{-- Or a simpler layout for printing --}}

@section('title', 'Laporan Presensi Karyawan - ' . $karyawan->nama_lengkap)

@push('styles')
    <style>
        body {
            font-family: 'sans-serif';
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h3, .report-header h4 {
            margin: 0;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            text-align: center;
            width: 200px; /* Adjust as needed */
        }
        .signature-block p {
            margin-bottom: 60px; /* Space for signature */
        }

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
            body {
                margin: 1cm; /* Adjust print margins */
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@section('content')
<div class="page-header d-print-none no-print">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Laporan Presensi: {{ $karyawan->nama_lengkap }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" onclick="window.print();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card card-lg">
            <div class="card-body">
                <div class="report-header">
                    <h3>LAPORAN PRESENSI KARYAWAN</h3>
                    <h4>Periode: {{ $namaBulan }} {{ $tahun }}</h4>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <p class="h3">{{ $karyawan->nama_lengkap }}</p>
                        <address>
                            NIK: {{ $karyawan->nik }}<br>
                            Jabatan: {{ $karyawan->jabatan }}<br>
                            Email: {{ $karyawan->email }}
                        </address>
                    </div>
                </div>

                <table class="table table-bordered table-vcenter">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Foto Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Foto Pulang</th>
                            <th>Keterangan</th>
                            <th>Total Jam Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanPresensi as $item)
                            <tr>
                                <td>{{ $item->tanggal_formatted }}</td>
                                <td>{{ $item->jam_in_formatted ?? '-' }}</td>
                                <td>
                                    @if ($item->foto_in)
                                        <img src="{{ Storage::url('uploads/absensi/' . $item->foto_in) }}" width="50" alt="Foto Masuk">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->jam_out_formatted ?? '-' }}</td>
                                <td>
                                    @if ($item->foto_out)
                                        <img src="{{ Storage::url('uploads/absensi/' . $item->foto_out) }}" width="50" alt="Foto Pulang">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->jam_in)
                                        @if ($item->is_late)
                                            <span class="text-danger">Terlambat</span>
                                        @else
                                            <span class="text-success">Tepat Waktu</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->jam_in && $item->jam_out)
                                        {{
                                            Carbon\Carbon::createFromTimeString($item->jam_out_formatted)
                                                ->diff(Carbon\Carbon::createFromTimeString($item->jam_in_formatted))
                                                ->format('%H Jam %I Menit')
                                        }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data presensi untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align:right; font-weight:bold;">Total Kehadiran:</td>
                            <td style="font-weight:bold;">{{ $totalHadir }} Hari</td>
                        </tr>
                        <tr>
                            <td colspan="6" style="text-align:right; font-weight:bold;">Total Terlambat:</td>
                            <td style="font-weight:bold;">{{ $totalTerlambat }} Hari</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="signature-section no-print">
                    <div class="signature-block">
                        <p>Mengetahui,</p>
                        <p>(_________________________)</p>
                        <p><strong>Manager HRD</strong></p>
                    </div>
                    <div class="signature-block">
                        <p>Dibuat oleh,</p>
                        <p>(_________________________)</p>
                        <p><strong>{{ Auth::guard('user')->user()->role ?? 'Administrator' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 