@extends('layouts.admin.tabler')

@section('title', 'Persetujuan Izin/Sakit Karyawan')

@push('styles')
<style>
    .status-badge {
        font-size: 0.8em;
        padding: 0.3em 0.6em;
        border-radius: 0.25rem;
    }
    .status-pending {
        background-color: #ffc107; /* Bootstrap warning yellow */
        color: #212529;
    }
    .status-approved {
        background-color: #28a745; /* Bootstrap success green */
        color: white;
    }
    .status-declined {
        background-color: #dc3545; /* Bootstrap danger red */
        color: white;
    }
    .action-buttons form {
        display: inline-block;
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Persetujuan Izin / Sakit Karyawan
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Pengajuan</h3>
                        <div class="ms-auto d-print-none">
                            <form method="GET" action="{{ route('admin.pengajuan_izin.index') }}" class="d-flex">
                                <select name="status_approved" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                    <option value="p" {{ request('status_approved', 'p') == 'p' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                    <option value="a" {{ request('status_approved') == 'a' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="d" {{ request('status_approved') == 'd' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="all" {{ request('status_approved') == 'all' ? 'selected' : '' }}>Semua</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body border-bottom py-3">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tgl. Pengajuan</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tgl. Izin</th>
                                        <th>Jenis</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th class="w-1">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuanIzin as $izin)
                                    <tr>
                                        <td>{{ $izin->created_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ $izin->karyawan->nik ?? '-' }}</td>
                                        <td>{{ $izin->karyawan->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $izin->tgl_izin->format('d-m-Y') }}</td>
                                        <td>{{ $izin->status == 'i' ? 'Izin' : ($izin->status == 's' ? 'Sakit' : 'N/A') }}</td>
                                        <td>{{ $izin->keterangan }}</td>
                                        <td>
                                            @if ($izin->status_approved == 'p')
                                                <span class="badge status-badge status-pending">Menunggu</span>
                                            @elseif ($izin->status_approved == 'a')
                                                <span class="badge status-badge status-approved">Disetujui</span>
                                            @elseif ($izin->status_approved == 'd')
                                                <span class="badge status-badge status-declined">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            @if ($izin->status_approved == 'p')
                                                <form action="{{ route('admin.pengajuan_izin.update_status', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status_approved" value="a">
                                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                                </form>
                                                <form action="{{ route('admin.pengajuan_izin.update_status', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status_approved" value="d">
                                                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pengajuan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        {{ $pengajuanIzin->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 