@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
<style>
    .webcam-capture,
    .webcam-capture video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }

    #map {
        height: 200px;
    }

</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
    </div>
</div>
<div class="row">
    <div class="col">
        @if ($cek > 0)
        <button id="takeabsen" class="btn btn-danger btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Pulang</button>
            @else
            <button id="takeabsen" class="btn btn-primary btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Masuk</button>
        @endif
        
    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <div id="map"></div>
    </div>
</div>

<audio id="notifikasi_in">
    <source src="{{ asset('assets/sound/notifikasi_absensi.mp3') }}" type="audio/mpeg">
</audio>
<audio id="notifikasi_out">
    <source src="{{ asset('assets/sound/notifikasi_absens2.mp3') }}" type="audio/mpeg">
</audio>
@endsection

@push('myscript')
<script>

    var notifikasi_in = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');
    Webcam.set({
        height: 480,
        width: 640,
        image_format: 'jpeg',
        jpeg_quality: 80
    });

    Webcam.attach('.webcam-capture');
    
    var lokasiInput = document.getElementById('lokasi');
    var officeLat = {{ $officeLatitude }};
    var officeLng = {{ $officeLongitude }};
    var officeRadius = {{ $radiusMeters }};
    var map = null; // Keep track of the map instance
    var userMarker = null;
    var officeCircle = null;

    // Function to initialize or update the map
    function updateUserLocationOnMap(lat, lon) {
        if (!map) {
            map = L.map('map').setView([lat, lon], 16); 
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            
            // Office Circle (shows configured radius)
            officeCircle = L.circle([officeLat, officeLng], { 
                color: 'blue',
                fillColor: '#3498db',
                fillOpacity: 0.3,
                radius: officeRadius // Use configured radius
            }).addTo(map);
            
            // Add a marker for the office location for clarity
             L.marker([officeLat, officeLng]).addTo(map)
                .bindPopup('Lokasi Kantor')
                .openPopup();
        }

        if (userMarker) {
            userMarker.setLatLng([lat, lon]);
        } else {
            userMarker = L.marker([lat, lon]).addTo(map);
        }
        map.setView([lat, lon], 16);
        userMarker.bindPopup('Lokasi Anda Saat Ini').openPopup();
    }

    function successCallback(position){
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        lokasiInput.value = lat + "," + lon;
        updateUserLocationOnMap(lat, lon);
        // Enable button after location is acquired
        $("#takeabsen").prop('disabled', false).html(originalButtonText); 
    }

    function errorCallback(error){
        var errorMessage = "Tidak bisa mendapatkan lokasi. Pastikan GPS aktif dan izinkan akses lokasi.";
        switch(error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = "Anda menolak permintaan untuk Geolokasi.";
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = "Informasi lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                errorMessage = "Permintaan untuk mendapatkan lokasi pengguna timeout.";
                break;
            case error.UNKNOWN_ERROR:
                errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                break;
        }
        Swal.fire({
            title: 'Error Lokasi!',
            text: errorMessage,
            icon: 'error',
        });
        // Re-enable button but indicate error or prompt to try again
        $("#takeabsen").prop('disabled', false).html(originalButtonText); 
    }

    var originalButtonText = $("#takeabsen").html();

    // Initial attempt to get location when page loads (optional, for faster map display)
    // if (navigator.geolocation){
    //     navigator.geolocation.getCurrentPosition(successCallback, errorCallback, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    // }
    // To ensure fresh location on click, we get it inside the click handler primarily.
    // For now, let's make sure the map is at least centered on the office if no user location yet.
    if (!map && officeLat && officeLng) {
        map = L.map('map').setView([officeLat, officeLng], 13); // Zoom out a bit if only office is shown
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        officeCircle = L.circle([officeLat, officeLng], { 
            color: 'blue',
            fillColor: '#3498db',
            fillOpacity: 0.3,
            radius: officeRadius
        }).addTo(map);
         L.marker([officeLat, officeLng]).addTo(map)
            .bindPopup('Lokasi Kantor')
            .openPopup();
    }


    $("#takeabsen").click(function(e){
        e.preventDefault(); // Prevent default form submission if any
        originalButtonText = $(this).html(); // Store original text
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengambil lokasi...');

        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(
                function(position) { // Success part of fetching location
                    var lat = parseFloat(position.coords.latitude.toFixed(6));
                    var lon = parseFloat(position.coords.longitude.toFixed(6));
                    lokasiInput.value = lat + "," + lon;
                    updateUserLocationOnMap(lat, lon);

                    // Proceed with webcam snap and AJAX only after location is successful
                    Webcam.snap(function(uri){
                        image = uri;
                        var currentLokasi = lokasiInput.value; // Use the freshly acquired location

                        if (!currentLokasi) {
                            Swal.fire('Error!', 'Lokasi tidak berhasil didapatkan. Silakan coba lagi.', 'error');
                            $("#takeabsen").prop('disabled', false).html(originalButtonText);
                            return;
                        }

                        $.ajax({
                            type:'POST',
                            url: '/presensi/store',
                            data: {
                                _token: "{{ csrf_token() }}",
                                image: image,
                                lokasi: currentLokasi // Send the fresh location
                            }
                            , cache: false
                            , success: function(respond){
                                // var status = respond.split("|"); // Old way
                                // Assuming respond is now JSON as per PresensiController change for store success
                                if (respond.status === "success"){
                                    if(respond.type === "in"){
                                        notifikasi_in.play();
                                    } else if (respond.type === "out"){
                                        notifikasi_out.play();
                                    }
                                    Swal.fire({
                                        title: 'Berhasil !',
                                        text: respond.message,
                                        icon: 'success'
                                    })
                                    setTimeout("location.href='/dashboard'", 3000);
                                } else{
                                     // Assuming respond is JSON with status and message for error as well
                                    Swal.fire({
                                        title: 'Error !',
                                        text: respond.message || 'Terjadi kesalahan saat menyimpan presensi.',
                                        icon: 'error',
                                    })
                                    $("#takeabsen").prop('disabled', false).html(originalButtonText);
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                var errorMessage = 'Gagal menghubungi server.';
                                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                                    errorMessage = jqXHR.responseJSON.message;
                                } else if (jqXHR.responseText) {
                                    try {
                                        var response = JSON.parse(jqXHR.responseText);
                                        if(response.message) errorMessage = response.message;
                                    } catch (e) { /* ignore parsing error */ }
                                }
                                Swal.fire('Error!', errorMessage, 'error');
                                $("#takeabsen").prop('disabled', false).html(originalButtonText);
                            }
                        });
                    });
                },
                function(error) { // Error part of fetching location
                    errorCallback(error); // Use the centralized error callback
                    // Button state already handled in errorCallback
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 } // Options for high accuracy & longer timeout
            );
        } else {
            Swal.fire('Error!', 'Browser Anda tidak mendukung Geolokasi.', 'error');
            $(this).prop('disabled', false).html(originalButtonText);
        }
    });

</script>
@endpush