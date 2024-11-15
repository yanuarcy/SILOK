@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
@endpush

@section('Dashboard')
    @include('admin.dashboard.header')
    @include('admin.dashboard.sidebar')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Loket</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('lokets.index') }}">Data Loket</a>
                    </div>
                    <div class="breadcrumb-item">Edit Loket</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Loket Form</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('lokets.update', $loket->id) }}" method="POST" id="editForm">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Front Office <span class="text-danger">*</span></label>
                                    <select class="form-control @error('user_id') is-invalid @enderror" name="user_id">
                                        <option value="">Pilih Front Office</option>
                                        @foreach($frontOfficeUsers as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $loket->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nomor Loket <span class="text-danger">*</span></label>
                                    <select class="form-control @error('loket_number') is-invalid @enderror" name="loket_number">
                                        <option value="">Pilih Nomor Loket</option>
                                        @foreach($availableLoketNumbers as $number)
                                            <option value="{{ $number }}" {{ old('loket_number', $loket->loket_number) == $number ? 'selected' : '' }}>
                                                Loket {{ $number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('loket_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $loket->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Status Aktif</label>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('lokets.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
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
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = $('#saveBtn');

                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = "{{ route('masterdata.loket') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Simpan Perubahan').prop('disabled', false);

                        // Reset validation states
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if(errors) {
                                Object.keys(errors).forEach(key => {
                                    $(`[name="${key}"]`)
                                        .addClass('is-invalid')
                                        .after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                                });
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.',
                        });
                    }
                });
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
