@extends('layouts.admin.tabler')

@section('title', 'Konfigurasi Lokasi Kantor')

{{-- @push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhO3OCcMSBXjrRPiQmOeVcHZGtVRTYMgggoI="
    crossorigin=""/>
<style>
    #map { height: 400px; }
</style>
@endpush --}}

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Konfigurasi Lokasi Kantor
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
                    <div class="card-body">
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
                        <form action="{{ route('admin.konfigurasi.lokasi.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Lokasi Kantor</label>
                                <input type="text" class="form-control" name="lokasi_kantor" id="lokasi_kantor" placeholder="Nama/Alamat Kantor" value="{{ old('lokasi_kantor', $konfigurasi->lokasi_kantor ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Lokasi di Peta atau Masukkan Manual</label>
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Latitude</label>
                                        <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Contoh: -6.1234567" value="{{ old('latitude', $konfigurasi->latitude ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Longitude</label>
                                        <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Contoh: 106.1234567" value="{{ old('longitude', $konfigurasi->longitude ?? '') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Radius (meter)</label>
                                <input type="number" class="form-control" name="radius" placeholder="Contoh: 50" value="{{ old('radius', $konfigurasi->radius ?? '') }}" required min="1">
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary w-100">Simpan Konfigurasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const initialLatitude = {{ old('latitude', $konfigurasi->latitude ?? -6.200000) }}; // Default to Jakarta if no config
    const initialLongitude = {{ old('longitude', $konfigurasi->longitude ?? 106.816666) }}; // Default to Jakarta if no config
    const initialZoom = {{ ($konfigurasi->latitude ?? null) ? 15 : 10 }}; // Zoom further if coords exist

    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');

    const map = L.map('map').setView([initialLatitude, initialLongitude], initialZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let marker = L.marker([initialLatitude, initialLongitude], {
        draggable: true
    }).addTo(map);

    marker.on('dragend', function (event) {
        const position = marker.getLatLng();
        latInput.value = position.lat.toFixed(7);
        lonInput.value = position.lng.toFixed(7);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(7);
        lonInput.value = e.latlng.lng.toFixed(7);
    });

    function updateMarkerPosition() {
        const lat = parseFloat(latInput.value);
        const lon = parseFloat(lonInput.value);
        if (!isNaN(lat) && !isNaN(lon)) {
            const newLatLng = L.latLng(lat, lon);
            marker.setLatLng(newLatLng);
            map.panTo(newLatLng);
        }
    }

    latInput.addEventListener('change', updateMarkerPosition);
    lonInput.addEventListener('change', updateMarkerPosition);

    // Set initial values to inputs if they are empty and config exists (for page load)
    if (latInput.value === '' && {{ Js::from($konfigurasi->latitude ?? null) }} !== null) {
        latInput.value = initialLatitude.toFixed(7);
    }
    if (lonInput.value === '' && {{ Js::from($konfigurasi->longitude ?? null) }} !== null) {
        lonInput.value = initialLongitude.toFixed(7);
    }
});
</script>
@endpush 