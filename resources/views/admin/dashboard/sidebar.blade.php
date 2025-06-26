@php
    include_once app_path('Helpers/GeneralSettings.php');
@endphp

<style>
    /* Badge notification styling */
    .nav-item .badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
        min-width: 18px;
        text-align: center;
    }

    /* Animation untuk badge baru */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .badge-pulse {
        animation: pulse 2s infinite;
    }

    /* Badge di dropdown menu */
    .dropdown-menu .nav-link .badge {
        font-size: 9px;
        padding: 1px 5px;
        margin-left: 5px;
    }

    /* Hover effect untuk menu dengan notifikasi */
    .nav-item.dropdown:hover .badge {
        background-color: #fff !important;
        color: #17a2b8 !important;
    }

    /* Badge warna untuk surat masuk */
    .badge-success {
        background: linear-gradient(45deg, #28a745, #20c997) !important;
    }

    .badge-info {
        background: linear-gradient(45deg, #17a2b8, #007bff) !important;
    }

    /* Responsiveness untuk mobile */
    @media (max-width: 768px) {
        .nav-item .badge {
            font-size: 9px;
            padding: 1px 4px;
            min-width: 16px;
        }
    }
</style>

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">{{ getSiteName() }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">Sl</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Overview</li>
            <li class="nav-item dropdown {{ $type_menu === 'dashboard' ? 'active' : '' }}">
                <a href="#"
                    class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('Dashboard/General') ? 'active' : '' }}'>
                        <a class="nav-link"
                            href="{{ route('Dashboard.General') }}">General Dashboard</a>
                    </li>
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Operator' || auth()->user()->role === 'Front Office'))
                        <li class="{{ Request::is('Dashboard/Front-Office') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('Dashboard.FrontOffice') }}">Front-Office Dashboard</a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('home') }}"><i class="fas fa-home"></i> <span>Home</span></a>
            </li>
            <li class="menu-header">Features</li>
            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Operator' || auth()->user()->role === 'Front Office' || auth()->user()->role === 'Back Office' || auth()->user()->role === 'Lurah'))
                <li class="nav-item dropdown {{ $type_menu === 'master-data' ? 'active' : '' }}">
                    <a href="#"
                        class="nav-link has-dropdown"
                        data-toggle="dropdown"><i class="fas fa-database"></i> <span>Master Data</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('Master-Data/Member', 'Master-Data/Member/*/edit') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('Member.index') }}">Data Member</a>
                        </li>
                        <li class="nav-item dropdown {{ Request::is('Master-Data/Data-Layanan') ? 'active' : '' }}">
                            <a class="nav-link has-dropdown"
                            data-toggle="dropdown" href="#"><span>Data Layanan</span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('Master-Data/Data-Layanan/Layanan', 'admin/layanan/create') ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('masterdata.layanan') }}">Layanan</a>
                                </li>
                                <li class="{{ Request::is('Master-Data/Data-Layanan/sub-layanan') ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('masterdata.sub-layanan') }}">Sub Layanan</a>
                                </li>
                                <li class="{{ Request::is('Master-Data/Data-Layanan/layanan-item') ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('masterdata.layanan-item') }}">Sub Layanan Item</a>
                                </li>
                                <li class="{{ Request::is('Master-Data/Data-Layanan/kategori-pendaftaran') ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('masterdata.kategori-pendaftaran') }}">Kategori Pendaftaran</a>
                                </li>
                                <li class="{{ Request::is('Master-Data/Data-Layanan/kategori-pemohon') ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('masterdata.kategori-pemohon') }}">Kategori Pemohon</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('Master-Data/Loket', 'Master-Data/Loket/create', 'Master-Data/Loket/*/edit') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('Loket.index') }}">Data Loket</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/ApiWhatsapp', 'Master-Data/ApiWhatsapp/create', 'Master-Data/ApiWhatsapp/*/edit') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('ApiWhatsapp.index') }}">Data API Whatsapp</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Antarmuka') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('Antarmuka.index') }}">Data Antarmuka</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Pemohon') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('masterdata.pemohon') }}">Data Pemohon</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Pegawai') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('Pegawai.index') }}">Data Pegawai</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Data-SKM') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.Data-SKM.index') }}">Data SKM</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Data-Kependudukan', 'Master-Data/Data-Kependudukan/edit') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.kependudukan.index') }}">Data Kependudukan</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Data-Perpu', 'Master-Data/Data-Perpu/create', 'Master-Data/Data-Perpu/{id}') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.Perpu.index') }}">Data Perpu</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/BankData', 'Master-Data/BankData/create', 'Master-Data/Data-Perpu/{id}') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.masterdata.BankData.index') }}">Data BankData</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Spesimen', 'Master-Data/Spesimen/create') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.masterdata.Spesimen.index') }}">Spesimen TTD & Stampel</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(auth()->check() && (auth()->user()->role === 'Ketua RT' || auth()->user()->role === 'Ketua RW' || auth()->user()->role === 'Operator'))
                <li class="nav-item dropdown {{ $type_menu === 'master-data' ? 'active' : '' }}">
                    <a href="#"
                        class="nav-link has-dropdown"
                        data-toggle="dropdown"><i class="fas fa-database"></i> <span>Master Data</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('Master-Data/BankData', 'Master-Data/BankData/create', 'Master-Data/Data-Perpu/{id}') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.masterdata.BankData.index') }}">Data BankData</a>
                        </li>
                        <li class="{{ Request::is('Master-Data/Spesimen', 'Master-Data/Spesimen/create') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.masterdata.Spesimen.index') }}">Spesimen TTD & Stampel</a>
                        </li>
                    </ul>
                </li>
            @endif
            {{-- <li class="{{ Request::is('psu') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('psu.index') }}"><i class="fas fa-file-alt"></i> <span>PSU</span></a>
            </li> --}}
            <li class="nav-item dropdown {{ $type_menu === 'psu' ? 'active' : '' }}">
                <a href="#"
                    class="nav-link has-dropdown">
                    <i class="fas fa-file-alt"></i>
                    <span>PSU</span>
                    {{-- Badge untuk total notifikasi surat masuk --}}
                    {{-- @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'user', 'Ketua RT', 'Ketua RW']))
                        <span class="badge badge-info ml-1" id="total-psu-notification" style="display: none;">0</span>
                    @endif --}}
                </a>
                <ul class="dropdown-menu">
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user' || auth()->user()->role === 'Ketua RT' || auth()->user()->role === 'Ketua RW' ))
                    <li class="{{ Request::is('psu','psu-permohonan-saya', 'psu/create') ? 'active' : '' }}">
                        <a class="nav-link"
                        href="{{ route('psu.permohonan-saya') }}">
                        Permohonan Saya
                        {{-- <i class="fas fa-file-alt mr-2"></i>Permohonan Saya --}}
                    </a>
                    </li>
                    @endif
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user'))
                        <li class="{{ Request::is('surat-masuk/psu') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('surat-masuk.psu.index') }}">
                                Surat Masuk
                                {{-- <i class="fas fa-inbox mr-2"></i>Surat Masuk --}}
                                {{-- Badge khusus untuk surat masuk --}}
                                {{-- <span class="badge badge-success ml-1" id="surat-masuk-psu-count" style="display: none;">0</span> --}}
                            </a>
                        </li>
                    @endif
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Ketua RT' || auth()->user()->role === 'Ketua RW' || auth()->user()->role === 'Front Office' || auth()->user()->role === 'Back Office' || auth()->user()->role === 'Lurah' ))
                        <li class="{{ Request::is('psu-semua-permohonan') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('psu.semua-permohonan') }}">
                                Semua Permohonan
                                {{-- <i class="fas fa-list mr-2"></i>Semua Permohonan --}}
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item dropdown {{ $type_menu === 'skaw' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-users"></i>
                    <span>SKAW</span>
                </a>
                <ul class="dropdown-menu">
                    {{-- Menu 1: Permohonan Saya - Untuk User dan Admin --}}
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user'))
                        <li class="{{ Request::is('skaw', 'skaw/permohonan-saya', 'skaw/create', 'skaw/*/edit') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('skaw.permohonan-saya') }}">
                                Permohonan Saya
                            </a>
                        </li>
                    @endif

                    {{-- Menu 2: Semua Permohonan - Untuk Front Office, Back Office, Lurah, Camat --}}
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Front Office' || auth()->user()->role === 'Back Office' || auth()->user()->role === 'Lurah' || auth()->user()->role === 'Camat'))
                        <li class="{{ Request::is('skaw/semua-permohonan') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('skaw.semua-permohonan') }}">
                                Semua Permohonan
                            </a>
                        </li>
                    @endif

                    {{-- Menu 3: Daftar Jadwal Sidang - Untuk semua role yang terlibat --}}
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user' || auth()->user()->role === 'Front Office' || auth()->user()->role === 'Back Office' || auth()->user()->role === 'Lurah' || auth()->user()->role === 'Camat'))
                        <li class="{{ Request::is('skaw/daftar-sidang') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('skaw.daftar-sidang') }}">
                                Daftar Jadwal Sidang
                            </a>
                        </li>
                    @endif

                    {{-- Menu 4: Berkas Pasca Sidang - Untuk approval Lurah dan Camat --}}
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user' || auth()->user()->role === 'Back Office' || auth()->user()->role === 'Lurah' || auth()->user()->role === 'Camat'))
                        <li class="{{ Request::is('skaw/telah-sidang') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('skaw.telah-sidang') }}">
                                Berkas Pasca Sidang
                            </a>
                        </li>
                    @endif

                    {{-- Menu 5: SKAW Selesai - Untuk melihat dokumen final --}}
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user' || auth()->user()->role === 'Front Office' || auth()->user()->role === 'Back Office' || auth()->user()->role === 'Lurah' || auth()->user()->role === 'Camat'))
                        <li class="{{ Request::is('skaw/skaw-jadi') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('skaw.skaw-jadi') }}">
                                SKAW Selesai
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="{{ Request::is('puntadewa', 'puntadewa/create') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('puntadewa.index') }}"><i class="fas fa-clipboard-list"></i> <span>Puntadewa</span></a>
            </li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('home') }}"><i class="fas fa-map-marker-alt"></i> <span>Verifikasi Domisili</span></a>
            </li>
            <li class="{{ Request::is('surat-pengantar', 'surat-pengantar/create') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('surat-pengantar.index') }}"><i class="fas fa-paper-plane"></i> <span>Surat Pengantar</span></a>
            </li>
            <li class="menu-header">Preferences</li>
            {{-- <li class="{{ Request::is('Profile') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('Profile.index') }}"><i class="fas fa-user"></i> <span>Profile</span></a>
            </li> --}}
            <li class="nav-item dropdown {{ $type_menu === 'profile' ? 'active' : '' }}">
                <a href="#"
                    class="nav-link has-dropdown"><i class="fas fa-user"></i><span>Profile</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('Profile') ? 'active' : '' }}'>
                        <a class="nav-link"
                            href="{{ route('Profile.index') }}">Informasi Profile</a>
                    </li>
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'user' || auth()->user()->role === 'Ketua RT' || auth()->user()->role === 'Ketua RW' ))
                        <li class="{{ Request::is('Profile/permohonan-saya') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('user-applications.index') }}">Permohonan Saya</a>
                        </li>
                    @endif
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'Ketua RT' || auth()->user()->role === 'Ketua RW' ))
                        <li class="{{ Request::is('Profile/user-applications/all') ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('user-applications.index-all') }}">Semua Permohonan</a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="{{ Request::is('Activities') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('activities.index') }}"><i class="fas fa-bolt"></i> <span>Activities</span></a>
            </li>
            <li class="{{ Request::is('Settings', 'Settings/General-Setting') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> <span>Settings</span></a>
            </li>

        </ul>
    </aside>
</div>

{{-- <script>
    $(document).ready(function() {
        // Function untuk load notification counts
        function loadPsuNotificationCounts() {
            console.log('testing load PSU notificationsssss');
            // Hanya untuk role yang memiliki akses ke surat masuk
            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'user', 'Ketua RT', 'Ketua RW']))

            // Load surat masuk count
            $.ajax({
                url: "{{ route('surat-masuk.psu.summary') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const totalSuratMasuk = response.data.total_surat_masuk || 0;
                        const newSuratToday = response.data.hari_ini || 0;

                        // Update badge surat masuk (tampilkan jika ada surat hari ini)
                        if (newSuratToday > 0) {
                            $('#surat-masuk-psu-count').text(newSuratToday).show();
                        } else {
                            $('#surat-masuk-psu-count').hide();
                        }

                        // Update total notification di menu utama
                        if (totalSuratMasuk > 0) {
                            $('#total-psu-notification').text(totalSuratMasuk).show();
                        } else {
                            $('#total-psu-notification').hide();
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error loading surat masuk notification:', xhr);
                    // Hide badges on error
                    $('#surat-masuk-psu-count').hide();
                    $('#total-psu-notification').hide();
                }
            });

            @endif
        }

        // Load initial counts
        loadPsuNotificationCounts();

        // Auto refresh every 60 seconds (1 minute)
        setInterval(loadPsuNotificationCounts, 60000);

        // Refresh when page becomes visible (user switches back to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                loadPsuNotificationCounts();
            }
        });

        // Refresh when user clicks on surat masuk menu
        $('a[href="{{ route('surat-masuk.psu.index') }}"]').on('click', function() {
            // Reset badge surat masuk karena user sudah buka halaman
            setTimeout(function() {
                $('#surat-masuk-psu-count').hide();
            }, 1000);
        });
    });
</script> --}}
