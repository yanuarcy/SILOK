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
                <h1>Edit Sub Layanan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('masterdata.sub-layanan') }}">Data Sub Layanan</a></div>
                    <div class="breadcrumb-item active">Edit Sub Layanan</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Existing Items Section -->
                            <div id="existingItems" class="mt-4">
                                <h5>Existing Items</h5>
                                @foreach($subLayanan->items as $index => $item)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <h6>{{ $item->title }}</h6>
                                                    @if($item->image)
                                                        <img src="{{ asset('img/layanan/' . $item->image) }}" height="50">
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="btn-group float-right">
                                                        <a href="{{ route('layanan-item.edit', $item->id) }}"
                                                           class="btn btn-warning btn-sm">Edit</a>
                                                        <form action="{{ route('layanan-item.destroy', $item->id) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                            data-name="{{ $item->title }}">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <form id="updateForm" action="{{ route('sub-layanan.update', $subLayanan->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                   name="title" value="{{ old('title', $subLayanan->title) }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            @if($subLayanan->image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('img/layanan/' . $subLayanan->image) }}" height="100">
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
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="hasItems"
                                               name="has_items" value="1" {{ $subLayanan->has_items ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="hasItems">Has Items</label>
                                    </div>
                                </div>

                                <!-- Add New Items Section -->
                                <div id="newItemsSection" class="mt-4" style="display: none;">
                                    <h5>Add New Items</h5>
                                    <div id="newItemsContainer"></div>
                                    <button type="button" class="btn btn-info" id="addNewItem">
                                        <i class="fas fa-plus"></i> Add New Item
                                    </button>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('masterdata.sub-layanan') }}" class="btn btn-secondary">Back</a>
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
            // Show/hide new items section based on checkbox
            $('#hasItems').change(function() {
                $('#newItemsSection').toggle(this.checked);
            });

            // Initialize if has items is checked
            if($('#hasItems').is(':checked')) {
                $('#newItemsSection').show();
            }

            // Add new item fields
            let newItemCount = {{ $subLayanan->items->count() }};
            $('#addNewItem').click(function() {
                const template = `
                    <div class="item-row card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="new_items[${newItemCount}][title]" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="new_items[${newItemCount}][image]" required>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-new-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#newItemsContainer').append(template);
                newItemCount++;
            });

            // Remove new item
            $(document).on('click', '.remove-new-item', function() {
                $(this).closest('.item-row').remove();
            });

            // Handle button submit
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                console.log('Form submitted');

                // Get form data
                const form = $(this);
                const formData = new FormData(this);

                // Add has_items value explicitly
                formData.set('has_items', $('#hasItems').is(':checked') ? '1' : '0');

                // Validation for hasItems
                if($('#hasItems').is(':checked')) {
                    const hasNewItems = $('#newItemsContainer').children().length > 0;
                    const hasExistingItems = {{ $subLayanan->items->count() }} > 0;

                    if(!hasNewItems && !hasExistingItems) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please add at least one item or uncheck "Has Items"',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                }

                // Log form data for debugging
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

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
                            text: 'Sub Layanan updated successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('masterdata.sub-layanan') }}";
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
                        $.ajax({
                            url: form.attr('action'),
                            method: 'DELETE',
                            data: form.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                            'info' // Changed from 'error' to 'info' as it's not really an error
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
