@php
    $RouteSaatIni = Route::currentRouteName();
    include_once app_path('Helpers/GeneralSettings.php');

@endphp

<!-- Navbar Start -->
<div class="container-xxl position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary px-4 px-lg-5 py-3 py-lg-0">
        <a href="{{ route('home') }}" class="navbar-brand p-0">
            <h1 class="m-0">{{ getSiteName() }}</h1>
            <!-- <img src="images/img/logo.png" alt="Logo"> -->
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="{{ route('home') }}" class="nav-item nav-link {{ $RouteSaatIni === 'home' ? 'active' : '' }}">Home</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle {{ ($RouteSaatIni === 'About' || $RouteSaatIni === 'kepegawaian') ? 'active' : '' }}" data-bs-toggle="dropdown">About</a>
                    <div class="dropdown-menu m-0">
                        <a href="{{ route('About') }}" class="dropdown-item {{ $RouteSaatIni === 'About' ? 'active' : '' }}">Tentang Kami</a>
                        <a href="{{ route('kepegawaian') }}" class="dropdown-item {{ $RouteSaatIni === 'kepegawaian' ? 'active' : '' }}">Kepegawaian</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle {{ $RouteSaatIni === 'Adminduk' ? 'active' : '' }}" data-bs-toggle="dropdown">Layanan</a>
                    <div class="dropdown-menu m-0">
                        <a href="{{ route('informasi-umum.index') }}" class="dropdown-item">Informasi Umum</a>
                        <a href="{{ route('Adminduk') }}" class="dropdown-item {{ $RouteSaatIni === 'Adminduk' ? 'active' : '' }}">Layanan Adminduk</a>
                        <a href="team.html" class="dropdown-item">E-Surat</a>
                        <a href="{{ route('bankdata.index') }}" class="dropdown-item">Bank Data</a>
                        <a href="{{ route('perpu.index') }}" class="dropdown-item">Peraturan Perundang-undangan</a>
                        <a href="{{ route('skm.create') }}" class="dropdown-item">Survey Kepuasan Masyarakat (SKM)</a>
                    </div>
                </div>
                <a href="{{ route('Contact') }}" class="nav-item nav-link {{ $RouteSaatIni === 'Contact' ? 'active' : '' }}">Kontak</a>
                {{-- @guest
                @else
                    @if (auth()->user()->role == 'admin')
                    <a href="{{ route('Dashboard.General') }}" class="nav-item nav-link">Dashboard</a>

                    @endif
                @endguest --}}
            </div>
            @if (Route::has('login'))
                @auth
                    {{-- <a
                    href="{{ route('logout') }}"
                    class="btn btn-light rounded-pill text-primary py-2 px-4 ms-lg-5"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >
                    Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form> --}}
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img alt="image" src="{{ asset(Auth::user()->image ?? 'img/avatar/avatar-1.png') }}" class="rounded-circle me-2" style="width: 30px; height: 30px;">
                            <span class="d-none d-lg-inline-block text-dark">Hi, {{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu m-0">
                            <span class="dropdown-item disabled dropdown-title">Logged in {{ Auth::user()->getLoggedInDuration() }} ago</span>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('Dashboard.General') }}" class="dropdown-item">
                                <i class="fas fa-fire me-2"></i> Dashboard
                            </a>
                            <div class="dropdown-divider"></div>
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @endauth
                            @endif
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light rounded-pill text-primary py-2 px-4 ms-lg-5">Login</a>
                @endauth
            @endif
        </div>
    </nav>
</div>
<!-- Navbar End -->
