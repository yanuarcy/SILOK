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
                <h1>Edit Layanan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('masterdata.layanan') }}">Data Layanan</a></div>
                    <div class="breadcrumb-item active">Edit Layanan</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Existing Sub Layanan Section -->
                            <div id="existingSubLayanan" class="mt-4">
                                <h5>Existing Sub Layanan</h5>
                                @foreach($layanan->subLayanans as $index => $subLayanan)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <h6>{{ $subLayanan->title }}</h6>
                                                    @if($subLayanan->image)
                                                        <img src="{{ asset('img/layanan/' . $subLayanan->image) }}" height="50">
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="btn-group float-right">
                                                        <a href="{{ route('sub-layanan.edit', $subLayanan->id) }}"
                                                           class="btn btn-warning btn-sm">Edit</a>
                                                        <form action="{{ route('sub-layanan.destroy', $subLayanan->id) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                            data-name="{{ $subLayanan->title }}">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <form action="{{ route('layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title Layanan</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                   name="title" value="{{ old('title', $layanan->title) }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            @if($layanan->image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('img/layanan/' . $layanan->image) }}" height="100">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              name="description" rows="3" required>{{ old('description', $layanan->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Small Text</label>
                                    <input type="text" class="form-control @error('small') is-invalid @enderror"
                                           name="small" value="{{ old('small', $layanan->small) }}" required>
                                    @error('small')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="hasSubLayanan"
                                               name="has_sub_layanan" value="1" {{ $layanan->has_sub_layanan ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="hasSubLayanan">Has Sub Layanan</label>
                                    </div>
                                </div>



                                <!-- Add New Sub Layanan Section -->
                                <div id="newSubLayananSection" class="mt-4" style="display: none;">
                                    <h5>Add New Sub Layanan</h5>
                                    <div id="newSubLayananContainer"></div>
                                    <button type="button" class="btn btn-info" id="addNewSubLayanan">
                                        <i class="fas fa-plus"></i> Add New Sub Layanan
                                    </button>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('masterdata.layanan') }}" class="btn btn-secondary">Back</a>
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
            // Show/hide new sub layanan section based on checkbox
            $('#hasSubLayanan').change(function() {
                $('#newSubLayananSection').toggle(this.checked);
            });

            // Initialize if has sub layanan is checked
            if($('#hasSubLayanan').is(':checked')) {
                $('#newSubLayananSection').show();
            }

            // Add new sub layanan fields
            let newSubLayananCount = {{ $layanan->subLayanans->count() }};
            $('#addNewSubLayanan').click(function() {
                const template = `
                    <div class="sub-layanan-item card mb-3">
                        <div class="card-body">
                            <h6>New Sub Layanan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="new_sub_layanan[${newSubLayananCount}][title]" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="new_sub_layanan[${newSubLayananCount}][image]" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="new_sub_layanan[${newSubLayananCount}][has_items]" value="0">
                                    <input type="checkbox" class="custom-control-input has-items"
                                        id="hasNewItems${newSubLayananCount}"
                                        name="new_sub_layanan[${newSubLayananCount}][has_items]"
                                        value="1">
                                    <label class="custom-control-label" for="hasNewItems${newSubLayananCount}">Has Items</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-new-sub-layanan">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                `;
                $('#newSubLayananContainer').append(template);
                newSubLayananCount++;
            });

            // Remove new sub layanan
            $(document).on('click', '.remove-new-sub-layanan', function() {
                $(this).closest('.sub-layanan-item').remove();
            });

            // Handle form submission
            $('form').submit(function(e) {
                e.preventDefault();

                // Get form data
                const form = $(this);
                const formData = new FormData(this);

                // Add hidden input for unchecked checkboxes
                $('.has-items').each(function() {
                    const name = $(this).attr('name');
                    if (!formData.has(name)) {
                        formData.append(name, '0');
                    }
                });

                // Add has_sub_layanan value explicitly
                formData.set('has_sub_layanan', $('#hasSubLayanan').is(':checked') ? '1' : '0');

                // Send AJAX request
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        console.log('Success:', response);
                        Swal.fire({
                            title: 'Success!',
                            text: 'Layanan updated successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('masterdata.layanan') }}";
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText);
                        let errorMessage = 'Something went wrong while updating';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Preview image before upload
            $('input[type="file"]').change(function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    const input = $(this);
                    reader.onload = function(e) {
                        const preview = `<img src="${e.target.result}" height="100" class="mt-2">`;
                        input.closest('.form-group').find('img').not('[src^="/img/layanan/"]').remove();
                        input.after(preview);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Confirm delete
            $('.btn-delete').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name') || 'this item';

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-2',
                        cancelButton: 'btn btn-danger mx-2'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: `Are you sure you want to delete ${name}?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Get the CSRF token
                        const token = $('meta[name="csrf-token"]').attr('content');

                        // Get the delete URL from the form
                        const url = form.attr('action');

                        $.ajax({
                            url: url,
                            type: 'POST', // Changed to POST
                            data: {
                                _method: 'DELETE', // This simulates DELETE request
                                _token: token
                            },
                            success: function(response) {
                                if(response.success) {
                                    swalWithBootstrapButtons.fire(
                                        'Deleted!',
                                        response.message || 'The item has been deleted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    swalWithBootstrapButtons.fire(
                                        'Error!',
                                        response.message || 'Something went wrong while deleting.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                console.error('Delete error:', xhr.responseText);
                                let errorMessage = 'Something went wrong while deleting.';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                swalWithBootstrapButtons.fire(
                                    'Error!',
                                    errorMessage,
                                    'error'
                                );
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your data is safe :)',
                            'info'
                        );
                    }
                });
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
