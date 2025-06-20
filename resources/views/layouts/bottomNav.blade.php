<!-- App Bottom Menu -->
<div class="appBottomMenu">
        <a href="{{ route('karyawan.dashboard') }}" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        
        <a href="{{ route('karyawan.presensi.histori') }}" class="item {{ request()->is('presensi/histori') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Histori</strong>
            </div>
        </a>
        <a href="{{ route('karyawan.presensi.create') }}" class="item">
            <div class="col">
                <div class="action-button large">
                    <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                </div>
            </div>
        </a>
        <a href="{{ route('karyawan.izin.index') }}" class="item {{ request()->is('presensi/izin') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="calendar-outline" role="img" class="md hydrated" aria-label="calendar outline"></ion-icon>
                <strong>Izin</strong>
            </div>
        </a>
        <a href="{{ route('karyawan.profile.edit') }}" class="item {{ request()->is('editprofile') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
                <strong>Profil</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->