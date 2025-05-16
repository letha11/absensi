@extends('layouts.admin.tabler') {{-- Or a simpler layout for printing --}}

@section('title', 'Rekap Laporan Presensi - ' . $namaBulan . ' ' . $tahun)

@push('styles')
    <style>
        body {
            font-family: 'sans-serif';
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
            text-align: center;
        }
        .table-bordered thead th {
            vertical-align: middle;
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
                    Rekap Laporan Presensi: {{ $namaBulan }} {{ $tahun }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" onclick="window.print();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                    Cetak Rekap
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
                    <h3>REKAPITULASI LAPORAN PRESENSI KARYAWAN</h3>
                    <h4>Periode: {{ $namaBulan }} {{ $tahun }}</h4>
                </div>

                <table class="table table-bordered table-vcenter table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Total Hadir</th>
                            <th>Total Terlambat</th>
                            {{-- Add more columns if needed e.g., Izin, Sakit --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekapData as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['karyawan']->nik }}</td>
                                <td style="text-align: left;">{{ $data['karyawan']->nama_lengkap }}</td>
                                <td>{{ $data['karyawan']->jabatan }}</td>
                                <td>{{ $data['total_hadir'] }} Hari</td>
                                <td>{{ $data['total_terlambat'] }} Hari</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data presensi untuk direkap pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
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
                        <p><strong>Admin</strong></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection 