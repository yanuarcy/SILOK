@extends('Template.template')

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

    <link rel="stylesheet"
        href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Layanan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('masterdata.layanan') }}">Data Layanan</a></div>
                    <div class="breadcrumb-item active">Tambah Layanan</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6>Terjadi kesalahan:</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form action="{{ route('layanan.store') }}" id="layananForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title Layanan</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                   name="title" value="{{ old('title') }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kode Layanan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('kode_layanan') is-invalid @enderror"
                                                    name="kode_layanan" id="kodeLayanan" required>
                                                <option value="">Pilih Kode Layanan</option>
                                                @foreach(range('A', 'Z') as $letter)
                                                    <option value="{{ $letter }}" {{ old('kode_layanan') == $letter ? 'selected' : '' }}>
                                                        {{ $letter }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kode_layanan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="kodeInfo" class="kode-info" style="display: none;">
                                                <small><i class="fas fa-info-circle"></i> Informasi kode layanan akan ditampilkan di sini</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                   name="image" required accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, SVG. Max: 2MB</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Small Text <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('small') is-invalid @enderror"
                                                   name="small" value="{{ old('small') }}" required
                                                   placeholder="Teks pendek untuk deskripsi singkat">
                                            @error('small')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               name="image" required>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              name="description" rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="form-group">
                                    <label>Small Text</label>
                                    <input type="text" class="form-control @error('small') is-invalid @enderror"
                                           name="small" value="{{ old('small') }}" required>
                                    @error('small')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="hasSubLayanan"
                                               name="has_sub_layanan" value="1" {{ old('has_sub_layanan') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="hasSubLayanan">Has Sub Layanan</label>
                                    </div>
                                </div>

                                <div id="subLayananSection" style="display: none;">
                                    <div id="subLayananContainer">
                                        <!-- Sub Layanan fields will be added here dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-info" id="addSubLayanan">
                                        <i class="fas fa-plus"></i> Add Sub Layanan
                                    </button>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('masterdata.layanan') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>


    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>


    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Check kode layanan availability
            let checkKodeTimeout;
            $('#kodeLayanan').change(function() {
                const selectedKode = $(this).val();
                const infoDiv = $('#kodeInfo');

                // Clear previous timeout
                clearTimeout(checkKodeTimeout);

                if (selectedKode) {
                    // Show loading state
                    infoDiv.removeClass('kode-unavailable kode-available');
                    infoDiv.html(`
                        <i class="fas fa-spinner fa-spin"></i>
                        <small>Memeriksa ketersediaan kode ${selectedKode}...</small>
                    `);
                    infoDiv.show();

                    // Debounce untuk menghindari request berlebihan
                    checkKodeTimeout = setTimeout(function() {
                        $.ajax({
                            url: '{{ route("layanan.checkKode") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                kode: selectedKode
                            },
                            success: function(response) {
                                console.log('Check kode response:', response); // Debug log

                                if (response.available) {
                                    infoDiv.removeClass('kode-unavailable').addClass('kode-available');
                                    infoDiv.html(`
                                        <i class="fas fa-check-circle"></i>
                                        <strong class="text-success">Kode ${selectedKode} tersedia</strong><br>
                                        <small>Kode ini dapat digunakan untuk layanan baru</small>
                                    `);
                                } else {
                                    infoDiv.removeClass('kode-available').addClass('kode-unavailable');
                                    infoDiv.html(`
                                        <i class="fas fa-exclamation-circle"></i>
                                        <strong class="text-danger">Kode ${selectedKode} sudah digunakan</strong><br>
                                        <small>Digunakan untuk: <strong>${response.used_by || 'Layanan tidak diketahui'}</strong></small>
                                    `);
                                }
                                infoDiv.show();
                            },
                            error: function(xhr, status, error) {
                                console.error('Error checking kode:', xhr.responseJSON); // Debug log

                                infoDiv.removeClass('kode-available').addClass('kode-unavailable');
                                let errorMessage = 'Tidak dapat memeriksa ketersediaan kode';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                infoDiv.html(`
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Error:</strong><br>
                                    <small>${errorMessage}</small>
                                `);
                                infoDiv.show();
                            }
                        });
                    }, 500); // 500ms debounce
                } else {
                    infoDiv.hide();
                }
            });

            // Show/hide sub layanan section based on checkbox
            $('#hasSubLayanan').change(function() {
                $('#subLayananSection').toggle(this.checked);
            });

            // Initialize if checkbox was checked (e.g., on validation fail)
            if($('#hasSubLayanan').is(':checked')) {
                $('#subLayananSection').show();
            }

            // Add sub layanan fields
            let subLayananCount = 0;
            $('#addSubLayanan').click(function() {
                const template = `
                    <div class="sub-layanan-item card mb-3">
                        <div class="card-body">
                            <h5>Sub Layanan ${subLayananCount + 1}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="sub_layanan[${subLayananCount}][title]" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="sub_layanan[${subLayananCount}][image]" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="sub_layanan[${subLayananCount}][has_items]" value="0">
                                    <input type="checkbox" class="custom-control-input has-items"
                                        id="hasItems${subLayananCount}"
                                        name="sub_layanan[${subLayananCount}][has_items]"
                                        value="1">
                                    <label class="custom-control-label" for="hasItems${subLayananCount}">Has Items</label>
                                </div>
                            </div>
                            <div class="items-section" style="display: none;">
                                <div class="items-container">
                                    <!-- Items will be added here -->
                                </div>
                                <button type="button" class="btn btn-info btn-sm add-item mb-3">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-sub-layanan">
                                <i class="fas fa-trash"></i> Remove Sub Layanan
                            </button>
                        </div>
                    </div>
                `;
                $('#subLayananContainer').append(template);
                subLayananCount++;
            });

            // Remove sub layanan
            $(document).on('click', '.remove-sub-layanan', function() {
                $(this).closest('.sub-layanan-item').remove();
            });

            // Toggle items section
            $(document).on('change', '.has-items', function() {
                $(this).closest('.sub-layanan-item').find('.items-section').toggle(this.checked);
            });

            // Add item fields
            $(document).on('click', '.add-item', function() {
                const subLayananIndex = $(this).closest('.sub-layanan-item').index();
                const itemsContainer = $(this).closest('.items-section').find('.items-container');
                const itemCount = itemsContainer.children().length;

                const template = `
                    <div class="item-row card mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Item Title</label>
                                        <input type="text" class="form-control"
                                            name="sub_layanan[${subLayananIndex}][items][${itemCount}][title]" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Item Image</label>
                                        <input type="file" class="form-control"
                                            name="sub_layanan[${subLayananIndex}][items][${itemCount}][image]" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                itemsContainer.append(template);
            });

            // Form submission dengan SweetAlert
            // $('form').on('submit', function(e) {
            //     e.preventDefault();

            //     // Validate kode layanan
            //     const selectedKode = $('#kodeLayanan').val();
            //     if (!selectedKode) {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: 'Kode layanan harus dipilih!'
            //         });
            //         return;
            //     }

            //     const form = $(this);
            //     const formData = new FormData(this);

            //     // Show loading
            //     Swal.fire({
            //         title: 'Menyimpan Data...',
            //         text: 'Mohon tunggu sebentar',
            //         allowOutsideClick: false,
            //         didOpen: () => {
            //             Swal.showLoading()
            //         }
            //     });

            //     $.ajax({
            //         url: form.attr('action'),
            //         method: 'POST',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(response) {
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Berhasil!',
            //                 text: response.message,
            //                 showConfirmButton: false,
            //                 timer: 1500
            //             }).then(() => {
            //                 window.location.href = "{{ route('masterdata.layanan') }}";
            //             });
            //         },
            //         error: function(xhr) {
            //             let message = 'Terjadi kesalahan!';
            //             if (xhr.responseJSON && xhr.responseJSON.message) {
            //                 message = xhr.responseJSON.message;
            //             }
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Oops...',
            //                 text: message
            //             });
            //         }
            //     });
            // });

            $('#layananForm').on('submit', function(e) {
                e.preventDefault();

                console.log('Form submitted'); // Debug log

                // ✅ Validasi kode layanan
                const selectedKode = $('#kodeLayanan').val();
                if (!selectedKode) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kode Layanan Required!',
                        text: 'Silakan pilih kode layanan terlebih dahulu!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // ✅ Validasi apakah kode tersedia
                if ($('#kodeInfo').hasClass('kode-unavailable')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kode Layanan Tidak Tersedia!',
                        text: 'Kode yang dipilih sudah digunakan. Silakan pilih kode lain!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const form = $(this);
                const formData = new FormData(this);

                console.log('FormData prepared'); // Debug log

                // ✅ Show loading dengan lebih detail
                Swal.fire({
                    title: 'Menyimpan Data...',
                    html: `
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Mohon tunggu, data sedang diproses...</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // ✅ AJAX Request dengan enhanced error handling
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 30000, // 30 seconds timeout
                    beforeSend: function() {
                        console.log('AJAX request started'); // Debug log
                    },
                    success: function(response) {
                        console.log('AJAX success response:', response); // Debug log

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            html: `
                                <div class="text-center">
                                    <p><strong>${response.message}</strong></p>
                                    ${response.data ? `
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <small class="text-muted">
                                                <strong>ID:</strong> ${response.data.id}<br>
                                                <strong>Kode:</strong> ${response.data.kode_layanan}<br>
                                                <strong>Slug:</strong> ${response.data.slug}
                                            </small>
                                        </div>
                                    ` : ''}
                                </div>
                            `,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('masterdata.layanan') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', {xhr: xhr, status: status, error: error}); // Debug log
                        console.error('Response:', xhr.responseJSON); // Debug log

                        let title = 'Oops...';
                        let message = 'Terjadi kesalahan yang tidak diketahui!';
                        let icon = 'error';

                        if (xhr.status === 422) {
                            // Validation errors
                            title = 'Validasi Gagal!';
                            icon = 'warning';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                message = '<ul class="text-left">';
                                Object.keys(errors).forEach(function(key) {
                                    errors[key].forEach(function(errorMsg) {
                                        message += `<li>${errorMsg}</li>`;
                                    });
                                });
                                message += '</ul>';
                            }
                        } else if (xhr.status === 500) {
                            // Server errors
                            title = 'Server Error!';
                            message = xhr.responseJSON && xhr.responseJSON.message
                                ? xhr.responseJSON.message
                                : 'Terjadi kesalahan pada server. Silakan coba lagi.';
                        } else if (xhr.status === 0) {
                            // Network errors
                            title = 'Koneksi Error!';
                            message = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                        } else {
                            // Other errors
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                        }

                        Swal.fire({
                            icon: icon,
                            title: title,
                            html: message,
                            confirmButtonText: 'OK',
                            footer: `<small class="text-muted">Status: ${xhr.status} ${xhr.statusText}</small>`
                        });
                    }
                });
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
