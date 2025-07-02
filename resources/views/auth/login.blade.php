<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Presensi Geolocation</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/web-logo.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="__manifest.json">
    <style>
        @media (min-width: 992px) {
            body {
                background-color: #f0f2f5 !important; /* Light grey background for the desktop area outside the mobile frame */
                display: flex !important; /* Use flexbox to center content vertically and horizontally */
                justify-content: center !important; /* Center content horizontally */
                align-items: center !important; /* Center content vertically */
                min-height: 100vh !important; /* Ensure body takes full viewport height */
                overflow: hidden !important; /* Prevent body scroll if content overfills */
            }

            #appCapsule {
                max-width: 450px !important; /* Max width for mobile view */
                margin: 0 auto !important; /* Center the content within the flex container */
                box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Optional: add a subtle shadow for framing */
                border-radius: 10px; /* Optional: rounded corners for the frame */
                overflow: auto !important; /* Allow internal scrolling if content is too tall */
                padding-top: 20px !important; /* Add top padding to the container */
                padding-bottom: 20px !important; /* Add bottom padding to the container */
            }

            /* Adjust content spacing within the framed view */
            .login-form {
                margin-top: 0 !important; /* Reset any default top margin */
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            /* Ensure fixed button group is handled correctly */
            .form-button-group {
                position: static !important; /* Force to normal document flow */
                max-width: 450px !important; /* Constrain to appCapsule width */
                margin: 20px auto 0 auto !important; /* Center it and add top margin */
                padding-left: 16px !important; /* Ensure padding is applied */
                padding-right: 16px !important;
                border-top: none !important; /* Remove border if it was fixed positioned */
            }

            /* Remove fixed positioning properties from base style.css that are overridden */
            .appHeader, .appBottomMenu {
                position: static !important;
                width: auto !important;
                left: auto !important;
                right: auto !important;
            }

            /* Adjust any sections that had absolute positioning and need to fit */
            .section {
                position: static !important; /* Ensure sections are in normal flow */
                width: auto !important;
                left: auto !important;
                right: auto !important;
                top: auto !important;
                bottom: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }
    </style>
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">

        <div class="login-form mt-1">
            <div class="section">
                <img src="{{ asset('assets/img/web-logo.png') }}" alt="image" class="form-image mb-3">
            </div>
            <div class="section mt-1">
                <h1>E-Presensi PT. DELTA PRATAMA KARYA</h1>
                <h4>Silahkan Login</h4>
            </div>
            <div class="section mt-1 mb-5">
                @php
                    $messagewarning = Session::get('warning');
                @endphp
                @if (Session::get('warning'))
                <div class="alert alert-outline-warning">
                    {{ $messagewarning }}
                </div>
                @endif
                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-links mt-2">
                        <div><a href="page-forgot-password.html" class="text-muted">Forgot Password?</a></div>
                    </div>

                    <div class="form-button-group">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Log in</button>
                    </div>

                </form>
            </div>
        </div>


    </div>
    <!-- * App Capsule -->



    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('assets/js/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('ssets/js/plugins/jquery-circle-progress/circle-progress.min.js') }}a"></script>
    <!-- Base Js File -->
    <script src="{{ asset('assets/js/base.js') }}"></script>


</body>

</html>