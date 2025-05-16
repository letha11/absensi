@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Karyawan
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('admin.karyawan.index') }}" method="GET" class="input-icon" id="searchForm">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ $searchTerm ?? '' }}" class="form-control" placeholder="Search NIK or Name...">
                </form>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if (Session::get('error'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No. HP</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($karyawans as $karyawan)
                                        <tr>
                                            <td>{{ $loop->iteration + ($karyawans->currentPage() - 1) * $karyawans->perPage() }}</td>
                                            <td>{{ $karyawan->nik }}</td>
                                            <td>{{ $karyawan->nama_lengkap }}</td>
                                            <td>{{ $karyawan->jabatan }}</td>
                                            <td>{{ $karyawan->no_hp }}</td>
                                            <td>
                                                @if (!empty($karyawan->foto))
                                                    <img src="{{ asset('storage/uploads/karyawan/' . $karyawan->foto) }}" class="avatar" alt="Foto Karyawan">
                                                @else
                                                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="avatar" alt="No Foto">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.karyawan.edit', $karyawan->nik) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.karyawan.destroy', $karyawan->nik) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-button">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Data Karyawan tidak ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {{ $karyawans->appends(['search' => $searchTerm])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // SweetAlert for delete confirmation
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default if it were a submit button
                const form = this.closest('.delete-form'); // Find the closest form

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data karyawan ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush 