@extends('layouts.admin.tabler')

@section('title', 'Monitoring Presensi - ' . $displayDate->translatedFormat('d F Y'))

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Monitoring Presensi ({{ $displayDate->translatedFormat('l, d F Y') }})
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-12">
                <form action="{{ route('admin.monitoring.presensi') }}" method="GET" class="row gy-2 gx-3 align-items-center">
                    <div class="col-auto">
                        <label for="tanggal" class="visually-hidden">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $selectedDate->toDateString() }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row row-deck row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $jumlahHadir }} Karyawan Hadir
                                </div>
                                <div class="text-muted">
                                    Total Kehadiran Hari Ini
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-exclamation" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.986 12.502a9 9 0 1 0 -10.972 8.406" /><path d="M12 7v5l3 3" /><path d="M19 16v3" /><path d="M19 22v.01" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $jumlahTerlambat }} Karyawan Terlambat
                                </div>
                                <div class="text-muted">
                                    Total Keterlambatan Hari Ini
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Peta Lokasi Presensi Hari Ini</h3>
                    </div>
                    <div class="card-body">
                        <div id="map_presensi_large" class="map-presensi-large"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Presensi Karyawan</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Jam Masuk</th>
                                    <th>Foto Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Foto Pulang</th>
                                    <th>Status</th>
                                    <th>Lokasi Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($presensiHariIni as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->karyawan->nik ?? '-' }}</td>
                                        <td>{{ $item->karyawan->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $item->karyawan->jabatan ?? '-' }}</td>
                                        <td>{{ $item->jam_in_formatted ?? '-' }}</td>
                                        <td>
                                            @if ($item->foto_in)
                                                <img src="{{ Storage::url('uploads/absensi/' . $item->foto_in) }}" class="avatar" alt="Foto Masuk">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->jam_out_formatted ?? '-' }}</td>
                                         <td>
                                            @if ($item->foto_out)
                                                <img src="{{ Storage::url('uploads/absensi/' . $item->foto_out) }}" class="avatar" alt="Foto Pulang">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->jam_in)
                                                @if ($item->is_late)
                                                    <span class="badge bg-danger me-1"></span> Terlambat
                                                @else
                                                    <span class="badge bg-success me-1"></span> Tepat Waktu
                                                @endif
                                            @else
                                                <span class="badge bg-warning me-1"></span> Belum Absen
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->lokasi_in ?? '-' }} {{-- Display lat/long string --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Belum ada data presensi untuk hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // Declare a global variable to store the map instance
    window.leafletMapInstance = null;

    document.addEventListener("DOMContentLoaded", function () {
        // Add event listener for date change to submit form
        const dateInput = document.getElementById('tanggal');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                this.form.submit();
            });
        }

        var officeLat = {{ $officeLatitude ?? 'null' }};
        var officeLng = {{ $officeLongitude ?? 'null' }};
        var officeRadius = {{ $officeRadius ?? 0 }};
        var presensiData = @json($presensiHariIni);

        var mapElement = document.getElementById('map_presensi_large');
        if (!mapElement) {
            console.error("Large map element not found.");
            return;
        }

        // Check if a map instance already exists and remove it
        if (window.leafletMapInstance) {
            window.leafletMapInstance.remove();
            window.leafletMapInstance = null; // Clear the reference
        }

        if (officeLat === null || officeLng === null) {
            mapElement.innerHTML = '<p class="text-center text-danger">Konfigurasi lokasi kantor belum diatur.</p>';
            return;
        }
        
        window.leafletMapInstance = L.map('map_presensi_large').setView([officeLat, officeLng], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(window.leafletMapInstance);

        // Office Marker and Radius
        L.marker([officeLat, officeLng]).addTo(window.leafletMapInstance)
            .bindPopup('Lokasi Kantor')
            .openPopup(); // Open by default
        
        if (officeRadius > 0) {
             L.circle([officeLat, officeLng], {
                color: 'blue',
                fillColor: '#3498db',
                fillOpacity: 0.3,
                radius: officeRadius
            }).addTo(window.leafletMapInstance);
        }

        var bounds = L.latLngBounds(); // To auto-zoom map
        if (officeLat && officeLng) {
            bounds.extend([officeLat, officeLng]); // Add office to bounds
        }
        
        var hasValidLocations = false;

        presensiData.forEach(function(item) {
            if (item.latitude && item.longitude) {
                var lat = parseFloat(item.latitude);
                var lon = parseFloat(item.longitude);
                if (!isNaN(lat) && !isNaN(lon)) {
                    hasValidLocations = true;
                    var markerColor = item.is_late ? 'red' : 'green';
                    var customIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style='background-color:${markerColor};width:1rem;height:1rem;border-radius:50%;border:2px solid white;box-shadow: 0 0 5px rgba(0,0,0,0.5);'></div>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });

                    var popupContent = `<b>${item.karyawan ? item.karyawan.nama_lengkap : 'N/A'}</b><br>
                                        NIK: ${item.karyawan ? item.karyawan.nik : 'N/A'}<br>
                                        Nama: ${item.karyawan ? item.karyawan.nama_lengkap : 'N/A'}
                                        <br>
                                        Jam Masuk: ${item.jam_in_formatted}<br>
                                        Status: ${item.is_late ? '<span class="text-danger">Terlambat</span>' : '<span class="text-success">Tepat Waktu</span>'}`;
                    
                    L.marker([lat, lon], {icon: customIcon}).addTo(window.leafletMapInstance)
                        .bindPopup(popupContent);
                    bounds.extend([lat, lon]);
                }
            }
        });
        
        // Use setTimeout to ensure the map container is fully rendered and sized
        setTimeout(function() {
            if (hasValidLocations && bounds.isValid()) {
                window.leafletMapInstance.fitBounds(bounds, { padding: [50, 50] });
            } else if (officeLat && officeLng) {
                window.leafletMapInstance.setView([officeLat, officeLng], 14);
            } else {
                window.leafletMapInstance.setView([0,0], 2);
            }
            window.leafletMapInstance.invalidateSize(); // Call invalidateSize after setting view/bounds
        }, 100); // 100ms delay, can be adjusted
    });
</script>
@endpush 