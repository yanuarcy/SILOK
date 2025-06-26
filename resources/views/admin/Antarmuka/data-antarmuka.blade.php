@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        /* Existing table styles... */
        .table-striped thead th {
            background-color: #f4f6f9;
            color: #34395e;
            font-weight: 600;
            padding: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .table-striped tbody tr {
            transition: all 0.3s ease;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:hover {
            background-color: #f4f6f9;
        }

        .table-striped tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        /* FIXED VOLUME CONTROL STYLING */
        .volume-control-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 150px;
            padding: 5px;
        }

        .volume-slider-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Custom Range Slider Styling */
        .volume-range-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 80px;
            height: 6px;
            border-radius: 3px;
            background: #ddd;
            outline: none;
            opacity: 0.7;
            transition: opacity 0.2s;
            cursor: pointer;
        }

        .volume-range-slider:hover {
            opacity: 1;
        }

        .volume-range-slider:focus {
            opacity: 1;
            outline: 2px solid #0d6efd;
            outline-offset: 2px;
        }

        /* Webkit browsers (Chrome, Safari, newer Edge) */
        .volume-range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #0d6efd;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .volume-range-slider::-webkit-slider-thumb:hover {
            background: #0b5ed7;
            transform: scale(1.1);
        }

        .volume-range-slider::-webkit-slider-track {
            width: 100%;
            height: 6px;
            cursor: pointer;
            background: #ddd;
            border-radius: 3px;
        }

        /* Firefox */
        .volume-range-slider::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #0d6efd;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .volume-range-slider::-moz-range-thumb:hover {
            background: #0b5ed7;
            transform: scale(1.1);
        }

        .volume-range-slider::-moz-range-track {
            width: 100%;
            height: 6px;
            cursor: pointer;
            background: #ddd;
            border-radius: 3px;
            border: none;
        }

        /* Volume percentage display */
        .volume-percentage {
            font-size: 11px;
            font-weight: 600;
            color: #0d6efd;
            min-width: 35px;
            text-align: center;
        }

        /* Loading states */
        .volume-updating .volume-range-slider {
            opacity: 0.5;
            pointer-events: none;
        }

        .volume-success .volume-percentage {
            color: #28a745 !important;
            transition: color 0.3s ease;
        }

        .volume-error .volume-percentage {
            color: #dc3545 !important;
            transition: color 0.3s ease;
        }

        /* Small loading spinner */
        .volume-loading {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #0d6efd;
            border-radius: 50%;
            animation: volume-spin 1s linear infinite;
            margin-left: 5px;
        }

        @keyframes volume-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Rest of existing styles... */
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .dataTables_wrapper {
            padding: 0 15px;
        }

        .dataTables_wrapper .dataTables_length select {
            margin: 0 5px;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
            margin-left: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding: 20px 0 !important;
            margin-top: 15px !important;
            border-top: 1px solid #e9ecef;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 20px 0 !important;
            margin-top: 15px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0 5px !important;
            padding: 5px 12px !important;
            border-radius: 4px !important;
            border: 1px solid #dee2e6 !important;
            background: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd !important;
            color: white !important;
            border-color: #0d6efd !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8f9fa !important;
            color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        .dataTables_wrapper .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding: 0 15px;
        }

        .dataTables_info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-group {
            padding: 0 15px;
            gap: 10px;
        }

        .btn-group .btn {
            border-radius: 4px !important;
            padding: 8px 20px;
            transition: all 0.2s ease;
        }

        .btn-group .btn.active {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
        }

        .table thead th {
            vertical-align: middle;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .video-preview {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .dataTables_empty {
            font-size: 16px !important;
            color: #6c757d !important;
            background: #f8f9fa !important;
            border-radius: 8px !important;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,.05) !important;
        }

        .dataTables_empty i {
            font-size: 48px !important;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 0 10px;
            }

            .btn-group {
                padding: 0 10px;
            }

            .volume-control-wrapper {
                min-width: 120px;
            }

            .volume-range-slider {
                width: 60px !important;
            }
        }
    </style>
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Antarmuka</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <h4>Kelola Video Antarmuka</h4>
                                <button class="btn btn-primary" onclick="createAntarmuka()">
                                    <i class="fas fa-plus"></i> Add Antarmuka
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="antarmuka-table">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>KETERANGAN</th>
                                            <th>NAMA</th>
                                            <th>DURASI VIDEO</th>
                                            <th>SUMBER</th>
                                            <th>PREVIEW</th>
                                            <th>VOLUME</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const table = $('#antarmuka-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('antarmuka.data') }}",
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', {xhr, error, thrown});
                        console.log('Response:', xhr.responseJSON);
                    }
                },
                columns: [
                    {
                        data: null,
                        className: 'text-center',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'keterangan', name: 'keterangan' },
                    { data: 'nama', name: 'nama' },
                    { data: 'durasi_video', name: 'durasi_video' },
                    { data: 'sumber_type', name: 'sumber_type' },
                    {
                        data: 'preview',
                        name: 'preview',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'volume_control',
                        name: 'volume_control',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[1, 'asc']],
                language: {
                    searchPlaceholder: "Search videos...",
                    lengthMenu: "Show _MENU_ entries",
                    emptyTable: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-video fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">No data available in table</h6>
                            <p class="text-muted small">Video antarmuka akan muncul di sini</p>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center my-5">
                            <div class="mb-3">
                                <i class="fas fa-search fs-1 text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted">No entries found</h6>
                            <p class="text-muted small">Tidak ada data yang sesuai dengan pencarian</p>
                        </div>
                    `,
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                }
            });

            // ===========================
            // FIXED VOLUME CONTROL HANDLER
            // ===========================
            let volumeUpdateTimeout = {};

            // Handle volume slider input (real-time display update)
            $(document).on('input', '.volume-range-slider', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const slider = $(this);
                const videoId = slider.data('video-id');
                const newVolume = parseInt(slider.val());
                const wrapper = slider.closest('.volume-control-wrapper');
                const percentageDisplay = wrapper.find('.volume-percentage');

                // Update display immediately for smooth UX
                percentageDisplay.text(newVolume + '%');

                console.log('Volume slider moved:', {
                    videoId: videoId,
                    newVolume: newVolume
                });
            });

            // Handle volume slider change (save to database with debounce)
            $(document).on('change', '.volume-range-slider', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const slider = $(this);
                const videoId = slider.data('video-id');
                const newVolume = parseInt(slider.val());
                const wrapper = slider.closest('.volume-control-wrapper');
                const percentageDisplay = wrapper.find('.volume-percentage');

                console.log('Volume slider change triggered:', {
                    videoId: videoId,
                    newVolume: newVolume
                });

                // Clear previous timeout for this video
                if (volumeUpdateTimeout[videoId]) {
                    clearTimeout(volumeUpdateTimeout[videoId]);
                }

                // Set loading state
                wrapper.addClass('volume-updating');
                percentageDisplay.after('<span class="volume-loading"></span>');

                // Debounce the API call
                volumeUpdateTimeout[videoId] = setTimeout(function() {
                    updateVolumeToDatabase(videoId, newVolume, wrapper, percentageDisplay);
                }, 800); // Wait 800ms after user stops changing
            });

            // Function to update volume to database
            function updateVolumeToDatabase(videoId, volume, wrapper, percentageDisplay) {
                console.log('Sending volume update to server:', {
                    videoId: videoId,
                    volume: volume
                });

                $.ajax({
                    url: `/antarmuka/${videoId}/volume`,
                    method: 'POST',
                    data: {
                        volume: volume,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    timeout: 10000, // 10 second timeout
                    success: function(response) {
                        console.log('Volume update success:', response);

                        if (response.success) {
                            // Show success state
                            wrapper.removeClass('volume-updating').addClass('volume-success');

                            // Remove success state after 1 second
                            setTimeout(() => {
                                wrapper.removeClass('volume-success');
                            }, 1000);

                            // Show success notification (optional)
                            showVolumeNotification('success', `Volume berhasil diatur ke ${volume}%`);
                        } else {
                            throw new Error(response.message || 'Unknown error');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('Volume update failed:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            textStatus: textStatus,
                            errorThrown: errorThrown,
                            response: xhr.responseJSON
                        });

                        // Show error state
                        wrapper.removeClass('volume-updating').addClass('volume-error');

                        // Remove error state after 2 seconds
                        setTimeout(() => {
                            wrapper.removeClass('volume-error');
                        }, 2000);

                        // Show error notification
                        const errorMessage = xhr.responseJSON?.message || 'Gagal memperbarui volume';
                        showVolumeNotification('error', errorMessage);

                        // Reset slider to previous value if needed
                        // (You might want to store the previous value somewhere)
                    },
                    complete: function() {
                        // Always remove loading state
                        wrapper.removeClass('volume-updating');
                        wrapper.find('.volume-loading').remove();
                    }
                });
            }

            // Function to show volume update notifications
            function showVolumeNotification(type, message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: type === 'success' ? 'success' : 'error',
                        title: message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    // Fallback to console
                    if (type === 'success') {
                        console.log('Success:', message);
                    } else {
                        console.error('Error:', message);
                    }
                }
            }

            // ===========================
            // DELETE HANDLER (unchanged)
            // ===========================
            $('#antarmuka-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name');

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: `Are you sure want to delete\n${name}?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            method: 'DELETE',
                            data: form.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function() {
                                table.ajax.reload();
                                swalWithBootstrapButtons.fire(
                                    'Deleted!',
                                    'Data antarmuka has been deleted.',
                                    'success'
                                );
                            },
                            error: function(xhr) {
                                swalWithBootstrapButtons.fire(
                                    'Error!',
                                    'Something went wrong while deleting.',
                                    'error'
                                );
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your data is safe :)',
                            'error'
                        );
                    }
                });
            });

            // ===========================
            // DEBUGGING FUNCTIONS
            // ===========================
            window.debugVolumeControls = function() {
                console.log('=== VOLUME CONTROLS DEBUG ===');
                $('.volume-range-slider').each(function() {
                    const slider = $(this);
                    console.log('Slider found:', {
                        videoId: slider.data('video-id'),
                        currentValue: slider.val(),
                        min: slider.attr('min'),
                        max: slider.attr('max')
                    });
                });
            };

            // Auto-debug on page load
            setTimeout(() => {
                if (window.location.search.includes('debug=volume')) {
                    window.debugVolumeControls();
                }
            }, 2000);
        });

        function createAntarmuka() {
            window.location.href = "{{ route('Antarmuka.create') }}";
        }

        // ===========================
        // GLOBAL DEBUGGING HELPERS
        // ===========================
        window.testVolumeUpdate = function(videoId, volume) {
            console.log('Testing volume update manually:', {videoId, volume});

            const wrapper = $(`.volume-range-slider[data-video-id="${videoId}"]`).closest('.volume-control-wrapper');
            const percentageDisplay = wrapper.find('.volume-percentage');

            if (wrapper.length === 0) {
                console.error('Volume control not found for video ID:', videoId);
                return;
            }

            updateVolumeToDatabase(videoId, volume, wrapper, percentageDisplay);
        };

        window.getAllVolumeStates = function() {
            const states = [];
            $('.volume-range-slider').each(function() {
                const slider = $(this);
                states.push({
                    videoId: slider.data('video-id'),
                    currentValue: slider.val(),
                    displayValue: slider.closest('.volume-control-wrapper').find('.volume-percentage').text()
                });
            });
            console.table(states);
            return states;
        };
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
