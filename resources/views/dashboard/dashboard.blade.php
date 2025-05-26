@extends('layouts.presensi')
@section('content')
<div class="section" id="user-section">
    <div id="user-detail" class="d-flex align-items-center">
        <div class="avatar">
            @if (!empty(Auth::guard('karyawan')->user()->foto))
                @php
                    $path = Storage::url('/uploads/karyawan/'.Auth::guard('karyawan')->user()->foto);
                @endphp
                <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height:60px; object-fit: cover;">
            @else
                <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded" style="height:60px; object-fit: cover;">
            @endif
        </div>
        <div id="user-info" class="flex-grow-1">
            <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
        </div>
        <div class="ms-auto">
            <a href="/proseslogout" class="text-danger" style="font-size: 28px;">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
        </div>
    </div>
</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/editprofile" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Cuti</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Histori</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence mr-2">
                                <ion-icon name="camera"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence mr-2">
                                <ion-icon name="camera"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div id="rekappresensi">
    <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center" style="padding: 10px 12px !important; display: flex; flex-direction: column; align-items: center;">
                    <span class="badge bg-danger" style="position: absolute; top:5px; right:8px; font-size:0.6rem;z-index:999">{{ $rekappresensi->jmlhadir }}</span>
                    <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary"></ion-icon>
                    <span style="font-size: 0.8rem; font-weight:500">Hadir</span>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center" style="padding: 10px 12px !important; display: flex; flex-direction: column; align-items: center;">
                    <span class="badge bg-danger" style="position: absolute; top:5px; right:8px; font-size:0.6rem;z-index:999">{{ $rekapizin->jmlizin ?? 0 }}</span>
                    <ion-icon name="reader-outline" style="font-size: 1.6rem;" class="text-success"></ion-icon>
                    <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center" style="padding: 10px 12px !important; display: flex; flex-direction: column; align-items: center;">
                    <span class="badge bg-danger" style="position: absolute; top:5px; right:8px; font-size:0.6rem;z-index:999">{{ $rekapizin->jmlsakit ?? 0 }}</span>
                    <ion-icon name="bag-add-outline" style="font-size: 1.6rem;" class="text-warning"></ion-icon>
                    <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center" style="padding: 10px 12px !important; display: flex; flex-direction: column; align-items: center;">
                    <span class="badge bg-danger" style="position: absolute; top:5px; right:8px; font-size:0.6rem;z-index:999">{{ $rekappresensi->jmlterlambat }}</span>
                    <ion-icon name="alarm-outline" style="font-size: 1.6rem;" class="text-danger"></ion-icon>
                    <span style="font-size: 0.8rem; font-weight:500">Telat</span>
                </div>
            </div>
        </div>
    </div>

</div>
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($historibulanini as $d)
                    <li>
                        <div class="item">
                            <img src="{{ $d->foto_in ? Storage::url('uploads/absensi/'.$d->foto_in) : asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="in">
                                <div>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</div>
                                <span class="badge badge-success">{{ $d->jam_in->format('H:i') }}</span>
                                <span class="badge badge-danger">{{ $d->jam_out != null ? $d->jam_out->format('H:i') : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboard as $d)
                    <li>
                        <div class="item">
                            <img src="{{ $d->foto_in ? Storage::url('uploads/absensi/'.$d->foto_in) : asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="image" class="image" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="in">
                                <div><b>{{ $d->karyawan->nama_lengkap }}</b><br>
                                    <small class="text-muted">{{ $d->karyawan->jabatan }}</small>
                                </div>
                                <span class="badge {{ $d->jam_in->format('H:i') < "07:00" ? "bg-success" : "bg-danger" }}">
                                    {{ $d->jam_in->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </li>  
                    @endforeach
                    
                    
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection