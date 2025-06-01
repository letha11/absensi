@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Ganti Password
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <form action="/panel/ganti-password" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">Password Saat Ini</label>
                                    <input type="password" class="form-control" name="current_password" placeholder="Masukkan password saat ini" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Password Baru</label>
                                    <input type="password" class="form-control" name="new_password" placeholder="Masukkan password baru" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Konfirmasi password baru" required>
                                </div>
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 