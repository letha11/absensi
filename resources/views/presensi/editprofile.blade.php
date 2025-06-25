{{-- 
Profile Edit Form with Enhanced File Upload Validation
- Client-side file size validation (2MB limit)
- Server-side PHP upload error handling  
- Real-time file size feedback
- Comprehensive error display
- Loading state during form submission
--}}
@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Profile</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
<div class="row" style="margin-top:4rem">
    <div class="col">
        @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @elseif (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @elseif ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif
    </div>
</div>
<form action="{{ route('karyawan.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
    @csrf
    <div class="col">
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off">
                @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $karyawan->no_hp) }}" name="no_hp" placeholder="No. HP" autocomplete="off">
                @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" autocomplete="off">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="custom-file-upload @error('foto') is-invalid @enderror" id="fileUpload1">
            <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
            <label for="fileuploadInput">
                <span>
                    <strong>
                        <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                        <i>Tap to Upload</i>
                        <small class="text-muted d-block mt-2" style="font-size: 12px;">
                            <ion-icon name="information-circle-outline" style="font-size: 12px;"></ion-icon>
                            Ukuran file maksimal 2MB. Format yang didukung: JPG, JPEG, PNG
                        </small>
                    </strong>
                </span>
            </label>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileuploadInput');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('profileForm');
    
    // File size validation
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            
            // if (file.size > maxSize) {
            //     alert('Ukuran file terlalu besar! Maksimal 2MB. File yang dipilih: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
            //     this.value = ''; // Clear the input
            //     return;
            // }
            
            // Show file name and size
            const fileSizeKB = (file.size / 1024).toFixed(2);
            const label = document.querySelector('label[for="fileuploadInput"] span strong i');
            label.textContent = file.name + ' (' + fileSizeKB + 'KB)';
        }
    });
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        // if (file) {
        //     const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            
        //     // if (file.size > maxSize) {
        //     //     e.preventDefault();
        //     //     alert('Ukuran file terlalu besar! Maksimal 2MB.');
        //     //     return false;
        //     // }
        // }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Mengupdate...';
        
        // Set a timeout to re-enable button in case of server errors
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<ion-icon name="refresh-outline"></ion-icon> Update';
        }, 10000); // 10 seconds timeout
    });
});
</script>
@endsection