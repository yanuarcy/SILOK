<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>BizConsult - Consulting HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&family=Nunito:400,600,700,800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    {{-- <link href="lib/animate/animate.min.css" rel="stylesheet"> --}}
    <link href="{{ Vite::asset('resources/lib/animate/animate.min.css') }}" rel="stylesheet">
    {{-- <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"> --}}
    <link href="{{ Vite::asset('resources/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ Vite::asset('resources/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    {{-- <link href="{{ Vite::asset('resources/css/style.css') }}" rel="stylesheet"> --}}
    @stack('style')

    <!-- Start GA -->
    <script async
        src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- END GA -->

</head>

<body>

        @yield('Content')

        <div id="app">
            <div class="main-wrapper">

                <!-- Dashboard -->
                @yield('Dashboard')

            </div>
        </div>
        @include('sweetalert::alert')



        <!-- Back to Top -->
        {{-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> --}}

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ Vite::asset('resources/lib/wow/wow.min.js') }}"></script>
    <script src="{{ Vite::asset('resources/lib/easing/easing.min.js') }}"></script>
    <script src="{{ Vite::asset('resources/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ Vite::asset('resources/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('scripts')

    <!-- Template Javascript -->
    <script src="{{ Vite::asset('resources/js/main.js') }}"></script>
</body>

</html>
