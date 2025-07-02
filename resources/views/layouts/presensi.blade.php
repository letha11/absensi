<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>E-Presensi PT. DELTA PRATAMA KARYA</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/web-logo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="__manifest.json">
    <style>
        @media (min-width: 992px) { /* Applies to desktop and larger screens */
            #appCapsule {
                max-width: 450px !important; /* Max width for mobile view */
                height: 100vh !important;
                margin: 0 auto; /* Center the content */
                box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Optional: add a subtle shadow for framing */
                border-radius: 10px; /* Optional: rounded corners for the frame */
                overflow: auto !important; /* Ensures content stays within bounds and scrolls if necessary */
                position: relative !important; /* Make it a positioning context */
            }
            body {
                background-color: #f0f2f5; /* Light grey background for the desktop area outside the mobile frame */
            }
            .appBottomMenu {
                max-width: 450px !important; /* Align with appCapsule */
                margin: 0 auto !important; /* Center the bottom menu */
                left: 0 !important;
                right: 0 !important;
                width: auto !important; /* Allow max-width to take effect */
                border-radius: 10px 10px 0 0; /* Optional: match appCapsule border-radius at bottom */
                box-shadow: 0 -3px 6px 0 rgba(0,0,0,0.1), 0 -1px 3px 0 rgba(0,0,0,0.08); /* Optional: add shadow to match appCapsule */
            }
            .appHeader {
                max-width: 450px !important; /* Align with appCapsule */
                margin: 0 auto !important; /* Center the header */
                left: 0 !important;
                right: 0 !important;
                width: auto !important; /* Allow max-width to take effect */
                border-radius: 0 0 10px 10px; /* Optional: match appCapsule border-radius at top */
                box-shadow: 0 3px 6px 0 rgba(0,0,0,0.1), 0 1px 3px 0 rgba(0,0,0,0.08); /* Optional: add shadow to match appCapsule */
            }
        }
    </style>
</head>

<body style="background-color:#e9ecef;">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    @yield('header')

    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->


    @include('layouts.bottomNav')
    
    @include('layouts.script')

</body>

</html>