@extends('Template.template')

{{-- @section('title', 'General Dashboard') --}}

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">

    <style>
        #visitorMap {
            height: 460px;
        }

        .custom-marker {
            background: none;
            border: none;
        }

        .marker-pin-svg {
            width: 24px;
            height: 24px;
            fill: #4680ff;
            filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
            transition: all 0.2s ease;
        }

        .custom-marker:hover .marker-pin-svg {
            fill: #2196f3;
            transform: scale(1.2);
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            padding: 5px;
        }

        .leaflet-popup-content {
            margin: 8px;
            line-height: 1.4;
        }

        /* Styling untuk kontrol zoom */
        .leaflet-control-zoom {
            border: none !important;
            margin: 15px !important;
        }

        .leaflet-control-zoom-in,
        .leaflet-control-zoom-out {
            background: #4680ff !important;
            color: white !important;
            border-radius: 4px !important;
            width: 30px !important;
            height: 30px !important;
            line-height: 30px !important;
            font-weight: bold !important;
            border: none !important;
        }

        .leaflet-control-zoom-in:hover,
        .leaflet-control-zoom-out:hover {
            background: #2196f3 !important;
        }
    </style>
@endpush


@section('Dashboard')

    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Member</h4>
                            </div>
                            <div class="card-body">
                                10
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Layanan</h4>
                            </div>
                            <div class="card-body">
                                42
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pemohon</h4>
                            </div>
                            <div class="card-body">
                                1,201
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Online User</h4>
                            </div>
                            <div class="card-body">
                                47
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistics</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <a href="#"
                                        class="btn btn-primary">Week</a>
                                    <a href="#"
                                        class="btn">Month</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart"
                                height="208"></canvas>
                            <div class="statistic-details mt-sm-4">
                                <div class="statistic-details-item">
                                    <span class="text-muted"><span class="text-primary"><i
                                                class="fas fa-caret-up"></i></span> 7%</span>
                                    <div class="detail-value">$243</div>
                                    <div class="detail-name">Today's Sales</div>
                                </div>
                                <div class="statistic-details-item">
                                    <span class="text-muted"><span class="text-danger"><i
                                                class="fas fa-caret-down"></i></span> 23%</span>
                                    <div class="detail-value">$2,902</div>
                                    <div class="detail-name">This Week's Sales</div>
                                </div>
                                <div class="statistic-details-item">
                                    <span class="text-muted"><span class="text-primary"><i
                                                class="fas fa-caret-up"></i></span>9%</span>
                                    <div class="detail-value">$12,821</div>
                                    <div class="detail-name">This Month's Sales</div>
                                </div>
                                <div class="statistic-details-item">
                                    <span class="text-muted"><span class="text-primary"><i
                                                class="fas fa-caret-up"></i></span> 19%</span>
                                    <div class="detail-value">$92,142</div>
                                    <div class="detail-name">This Year's Sales</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Activities</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                <li class="media">
                                    <img class="rounded-circle mr-3"
                                        width="50"
                                        src="{{ asset('img/avatar/avatar-1.png') }}"
                                        alt="avatar">
                                    <div class="media-body">
                                        <div class="text-primary float-right">Now</div>
                                        <div class="media-title">Farhan A Mujib</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                                <li class="media">
                                    <img class="rounded-circle mr-3"
                                        width="50"
                                        src="{{ asset('img/avatar/avatar-2.png') }}"
                                        alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right">12m</div>
                                        <div class="media-title">Ujang Maman</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                                <li class="media">
                                    <img class="rounded-circle mr-3"
                                        width="50"
                                        src="{{ asset('img/avatar/avatar-3.png') }}"
                                        alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right">17m</div>
                                        <div class="media-title">Rizal Fakhri</div>
                                        <span class="text-small text-muted">Cras sit amet nibh libero, in gravida nulla.
                                            Nulla vel metus scelerisque ante sollicitudin.</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="pt-1 pb-1 text-center">
                                <a href="#"
                                    class="btn btn-primary btn-lg btn-round">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Doughnut Chart</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart3"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Authors</h4>
                        </div>
                        <div class="card-body">
                            <div class="row pb-2">
                                <div class="col-6 col-sm-3 col-lg-2 mb-md-0 mb-4">
                                    <div class="avatar-item mb-0">
                                        <img alt="image"
                                            src="{{ asset('img/avatar/avatar-5.png') }}"
                                            class="img-fluid"
                                            data-toggle="tooltip"
                                            title="Alfa Zulkarnain">
                                        <div class="avatar-badge"
                                            title="Editor"
                                            data-toggle="tooltip"><i class="fas fa-wrench"></i></div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-2 mb-md-0 mb-4">
                                    <div class="avatar-item mb-0">
                                        <img alt="image"
                                            src="{{ asset('img/avatar/avatar-4.png') }}"
                                            class="img-fluid"
                                            data-toggle="tooltip"
                                            title="Egi Ferdian">
                                        <div class="avatar-badge"
                                            title="Admin"
                                            data-toggle="tooltip"><i class="fas fa-cog"></i></div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-2 mb-md-0 mb-4">
                                    <div class="avatar-item mb-0">
                                        <img alt="image"
                                            src="{{ asset('img/avatar/avatar-1.png') }}"
                                            class="img-fluid"
                                            data-toggle="tooltip"
                                            title="Jaka Ramadhan">
                                        <div class="avatar-badge"
                                            title="Author"
                                            data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-2 mb-md-0 mb-4">
                                    <div class="avatar-item mb-0">
                                        <img alt="image"
                                            src="{{ asset('img/avatar/avatar-2.png') }}"
                                            class="img-fluid"
                                            data-toggle="tooltip"
                                            title="Ryan">
                                        <div class="avatar-badge"
                                            title="Admin"
                                            data-toggle="tooltip"><i class="fas fa-cog"></i></div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-2 mb-md-0 mb-4">
                                    <div class="avatar-item mb-0">
                                        <img alt="image"
                                            src="{{ asset('img/avatar/avatar-2.png') }}"
                                            class="img-fluid"
                                            data-toggle="tooltip"
                                            title="Ryan">
                                        <div class="avatar-badge"
                                            title="Admin"
                                            data-toggle="tooltip"><i class="fas fa-cog"></i></div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-2 mb-md-0 mb-4">
                                    <div class="avatar-item mb-0">
                                        <img alt="image"
                                            src="{{ asset('img/avatar/avatar-2.png') }}"
                                            class="img-fluid"
                                            data-toggle="tooltip"
                                            title="Ryan">
                                        <div class="avatar-badge"
                                            title="Admin"
                                            data-toggle="tooltip"><i class="fas fa-cog"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Referral URL</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="text-small font-weight-bold text-muted float-right">2,100</div>
                                <div class="font-weight-bold mb-1">Google</div>
                                <div class="progress"
                                    data-height="3">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="80%"
                                        aria-valuenow="80"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="text-small font-weight-bold text-muted float-right">1,880</div>
                                <div class="font-weight-bold mb-1">Facebook</div>
                                <div class="progress"
                                    data-height="3">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="67%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="text-small font-weight-bold text-muted float-right">1,521</div>
                                <div class="font-weight-bold mb-1">Bing</div>
                                <div class="progress"
                                    data-height="3">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="58%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="text-small font-weight-bold text-muted float-right">884</div>
                                <div class="font-weight-bold mb-1">Yahoo</div>
                                <div class="progress"
                                    data-height="3">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="36%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="text-small font-weight-bold text-muted float-right">473</div>
                                <div class="font-weight-bold mb-1">Kodinger</div>
                                <div class="progress"
                                    data-height="3">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="28%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="text-small font-weight-bold text-muted float-right">418</div>
                                <div class="font-weight-bold mb-1">Multinity</div>
                                <div class="progress"
                                    data-height="3">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="20%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Popular Browser</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col text-center">
                                    <div class="browser browser-chrome"></div>
                                    <div class="font-weight-bold mt-2">Chrome</div>
                                    <div class="text-muted text-small"><span class="text-primary"><i
                                                class="fas fa-caret-up"></i></span> 48%</div>
                                </div>
                                <div class="col text-center">
                                    <div class="browser browser-firefox"></div>
                                    <div class="font-weight-bold mt-2">Firefox</div>
                                    <div class="text-muted text-small"><span class="text-primary"><i
                                                class="fas fa-caret-up"></i></span> 26%</div>
                                </div>
                                <div class="col text-center">
                                    <div class="browser browser-safari"></div>
                                    <div class="font-weight-bold mt-2">Safari</div>
                                    <div class="text-muted text-small"><span class="text-danger"><i
                                                class="fas fa-caret-down"></i></span> 14%</div>
                                </div>
                                <div class="col text-center">
                                    <div class="browser browser-opera"></div>
                                    <div class="font-weight-bold mt-2">Opera</div>
                                    <div class="text-muted text-small">7%</div>
                                </div>
                                <div class="col text-center">
                                    <div class="browser browser-internet-explorer"></div>
                                    <div class="font-weight-bold mt-2">IE</div>
                                    <div class="text-muted text-small"><span class="text-primary"><i
                                                class="fas fa-caret-up"></i></span> 5%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Best Products</h4>
                        </div>
                        <div class="card-body">
                            <div class="owl-carousel owl-theme"
                                id="products-carousel">
                                <div>
                                    <div class="product-item pb-3">
                                        <div class="product-image">
                                            <img alt="image"
                                                src="{{ asset('img/products/product-4-50.png') }}"
                                                class="img-fluid">
                                        </div>
                                        <div class="product-details">
                                            <div class="product-name">iBook Pro 2018</div>
                                            <div class="product-review">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div class="text-muted text-small">67 Sales</div>
                                            <div class="product-cta">
                                                <a href="#"
                                                    class="btn btn-primary">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="product-item">
                                        <div class="product-image">
                                            <img alt="image"
                                                src="{{ asset('img/products/product-3-50.png') }}"
                                                class="img-fluid">
                                        </div>
                                        <div class="product-details">
                                            <div class="product-name">oPhone S9 Limited</div>
                                            <div class="product-review">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half"></i>
                                            </div>
                                            <div class="text-muted text-small">86 Sales</div>
                                            <div class="product-cta">
                                                <a href="#"
                                                    class="btn btn-primary">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="product-item">
                                        <div class="product-image">
                                            <img alt="image"
                                                src="{{ asset('img/products/product-1-50.png') }}"
                                                class="img-fluid">
                                        </div>
                                        <div class="product-details">
                                            <div class="product-name">Headphone Blitz</div>
                                            <div class="product-review">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                            <div class="text-muted text-small">63 Sales</div>
                                            <div class="product-cta">
                                                <a href="#"
                                                    class="btn btn-primary">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-hero">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <h4>14</h4>
                            <div class="card-description">Customers need help</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="tickets-list">
                                <a href="#"
                                    class="ticket-item">
                                    <div class="ticket-title">
                                        <h4>My order hasn't arrived yet</h4>
                                    </div>
                                    <div class="ticket-info">
                                        <div>Laila Tazkiah</div>
                                        <div class="bullet"></div>
                                        <div class="text-primary">1 min ago</div>
                                    </div>
                                </a>
                                <a href="#"
                                    class="ticket-item">
                                    <div class="ticket-title">
                                        <h4>Please cancel my order</h4>
                                    </div>
                                    <div class="ticket-info">
                                        <div>Rizal Fakhri</div>
                                        <div class="bullet"></div>
                                        <div>2 hours ago</div>
                                    </div>
                                </a>
                                <a href="#"
                                    class="ticket-item">
                                    <div class="ticket-title">
                                        <h4>Do you see my mother?</h4>
                                    </div>
                                    <div class="ticket-info">
                                        <div>Syahdan Ubaidillah</div>
                                        <div class="bullet"></div>
                                        <div>6 hours ago</div>
                                    </div>
                                </a>
                                <a href="features-tickets.html"
                                    class="ticket-item ticket-more">
                                    View All <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-6 col-12">
                    <div class="card mt-sm-5 mt-md-0">
                        <div class="card-header">
                            <h4>Visitors</h4>
                        </div>
                        <div class="card-body">
                            <div id="visitorMap"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>This Week Stats</h4>
                            <div class="card-header-action">
                                <div class="dropdown">
                                    <a href="#"
                                        class="dropdown-toggle btn btn-primary"
                                        data-toggle="dropdown">Filter</a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#"
                                            class="dropdown-item has-icon"><i class="far fa-circle"></i> Electronic</a>
                                        <a href="#"
                                            class="dropdown-item has-icon"><i class="far fa-circle"></i> T-shirt</a>
                                        <a href="#"
                                            class="dropdown-item has-icon"><i class="far fa-circle"></i> Hat</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#"
                                            class="dropdown-item">View All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="summary">
                                <div class="summary-info">
                                    <h4>$1,053</h4>
                                    <div class="text-muted">Sold 3 items on 2 customers</div>
                                    <div class="d-block mt-2">
                                        <a href="#">View All</a>
                                    </div>
                                </div>
                                <div class="summary-item">
                                    <h6>Item List <span class="text-muted">(3 Items)</span></h6>
                                    <ul class="list-unstyled list-unstyled-border">
                                        <li class="media">
                                            <a href="#">
                                                <img class="mr-3 rounded"
                                                    width="50"
                                                    src="{{ asset('img/products/product-1-50.png') }}"
                                                    alt="product">
                                            </a>
                                            <div class="media-body">
                                                <div class="media-right">$405</div>
                                                <div class="media-title"><a href="#">PlayStation 9</a></div>
                                                <div class="text-muted text-small">by <a href="#">Hasan Basri</a>
                                                    <div class="bullet"></div> Sunday
                                                </div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <a href="#">
                                                <img class="mr-3 rounded"
                                                    width="50"
                                                    src="{{ asset('img/products/product-2-50.png') }}"
                                                    alt="product">
                                            </a>
                                            <div class="media-body">
                                                <div class="media-right">$499</div>
                                                <div class="media-title"><a href="#">RocketZ</a></div>
                                                <div class="text-muted text-small">by <a href="#">Hasan Basri</a>
                                                    <div class="bullet"></div> Sunday
                                                </div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <a href="#">
                                                <img class="mr-3 rounded"
                                                    width="50"
                                                    src="{{ asset('img/products/product-3-50.png') }}"
                                                    alt="product">
                                            </a>
                                            <div class="media-body">
                                                <div class="media-right">$149</div>
                                                <div class="media-title"><a href="#">Xiaomay Readme 4.0</a></div>
                                                <div class="text-muted text-small">by <a href="#">Kusnaedi</a>
                                                    <div class="bullet"></div> Tuesday
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="footer-left">
            Copyright &copy; 2018 <div class="bullet"></div> Design By
        </div>
        <div class="footer-right">
            2.3.0
        </div>
    </footer>
@endsection

@push('scripts')

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.indonesia.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>


    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    {{-- <script src="{{ asset('js/page/index.js') }}"></script> --}}
    <script src="{{ asset('js/page/modules-chartjs.js') }}"></script>

    <script>
        $("#products-carousel").owlCarousel({
            items: 3,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 5000,
            loop: true,
            responsive: {
                0: {
                    items: 2,
                },
                768: {
                    items: 2,
                },
                1200: {
                    items: 2,
                },
            },
        });

        $(document).ready(function() {
            console.log('Script running...'); // Debugging

            // Inisialisasi map berpusat di Jawa Timur
            var map = L.map('visitorMap').setView([-7.5360639, 112.2384017], 8);

            // Tambahkan tile layer dari OpenStreetMap
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {

                maxZoom: 19
            }).addTo(map);

            // Definisi marker/pin untuk kota-kota di Jawa Timur
            var cities = [
                {
                    name: "Surabaya",
                    coords: [-7.2575, 112.7521],
                    visitors: 150
                },
                {
                    name: "Malang",
                    coords: [-7.9666, 112.6326],
                    visitors: 120
                },
                {
                    name: "Sidoarjo",
                    coords: [-7.4478, 112.7183],
                    visitors: 90
                },
                {
                    name: "Gresik",
                    coords: [-7.1511, 112.6503],
                    visitors: 80
                },
                {
                    name: "Mojokerto",
                    coords: [-7.4704, 112.4401],
                    visitors: 70
                }
            ];

            // Custom icon untuk marker
            var customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<svg viewBox="0 0 24 24" class="marker-pin-svg">
                        <path d="M12 0C7.58 0 4 3.58 4 8c0 5.25 8 13 8 13s8-7.75 8-13c0-4.42-3.58-8-8-8zm0 11c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                    </svg>`,
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -20]
            });

            // Tambahkan marker untuk setiap kota
            cities.forEach(function(city) {
                L.marker(city.coords, {icon: customIcon})
                .bindPopup(
                    `<strong>${city.name}</strong><br>
                    Visitors: ${city.visitors}`
                )
                .addTo(map);
            });

            // Batasi view hanya ke area Jawa Timur
            var southWest = L.latLng(-9.0, 111.0);
            var northEast = L.latLng(-6.0, 115.0);
            var bounds = L.latLngBounds(southWest, northEast);
            map.setMaxBounds(bounds);
        });
    </script>


    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
