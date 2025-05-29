<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.2.0
* @link https://tabler.io
* Copyright 2018-2025 The Tabler Authors
* Copyright 2018-2025 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Admin - E-Presensi PT. DELTA PRATAMA KARYA</title>
    <link rel="icon" href="{{ asset('assets/img/web-logo.png') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset('assets/img/web-logo.png') }}" type="image/x-icon"/>
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('tabler/libs/jsvectormap/dist/jsvectormap.css?1744816593') }}" rel="stylesheet" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('tabler/dist/css/tabler.css?1744816593') }}" rel="stylesheet" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="{{ asset('tabler/dist/css/tabler-flags.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-socials.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-payments.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-vendors.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-marketing.css?1744816593') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-themes.css?1744816593') }}" rel="stylesheet" />
    <!-- END PLUGINS STYLES -->
    <!-- BEGIN DEMO STYLES -->
    <link href="{{ asset('tabler/preview/css/demo.css?1744816593') }}" rel="stylesheet" />
    <!-- END DEMO STYLES -->
    <!-- BEGIN CUSTOM FONT -->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
      @import url("https://rsms.me/inter/inter.css");
      .map-presensi-large {
            height: 500px; /* Height for the large map */
            width: 100%;
            border-radius: 4px;
            margin-bottom: 1rem; /* Space below the map */
        }
      @stack('styles')
    </style>
    <!-- END CUSTOM FONT -->
  </head>
  <body>
    <!-- BEGIN GLOBAL THEME SCRIPT -->
    {{-- <script src="{{ asset('tabler/dist/js/tabler-theme.min.js?1744816593') }}"></script> --}}
    <!-- END GLOBAL THEME SCRIPT -->
    <div class="page">
      <!--  BEGIN SIDEBAR  -->
      @include('layouts.admin.sidebar')
      <!--  END SIDEBAR  -->
      <!-- BEGIN NAVBAR  -->
      @include('layouts.admin.header')
      <!-- END NAVBAR  -->
      <div class="page-wrapper">
        @yield('content')
        <!--  BEGIN FOOTER  -->
        @include('layouts.admin.footer')
        <!--  END FOOTER  -->
      </div>
    </div>
  
    <!-- BEGIN PAGE LIBRARIES -->
    <script src="{{ asset('tabler/libs/apexcharts/dist/apexcharts.min.js?1744816593') }}" defer></script>
    <script src="{{ asset('tabler/libs/jsvectormap/dist/js/jsvectormap.min.js?1744816593') }}" defer></script>
    <script src="{{ asset('tabler/libs/jsvectormap/dist/maps/world.js?1744816593') }}" defer></script>
    <script src="{{ asset('tabler/libs/jsvectormap/dist/maps/world-merc.js?1744816593') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- END PAGE LIBRARIES -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('tabler/dist/js/tabler.min.js?1744816593') }}" defer></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN DEMO SCRIPTS -->
    <script src="{{ asset('tabler/preview/js/demo.min.js?1744816593') }}" defer></script>
    <!-- END DEMO SCRIPTS -->

    @include('layouts.script')
   
    @stack('myscript')
    @stack('scripts')

  </body>
</html>
