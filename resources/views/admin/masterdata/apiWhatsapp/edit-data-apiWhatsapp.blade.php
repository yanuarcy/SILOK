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
                <h1>Edit Whatsapp Owner</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('ApiWhatsapp.index') }}">Data Whatsapp Owner</a>
                    </div>
                    <div class="breadcrumb-item">Edit Whatsapp Owner</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-primary">Form Edit Whatsapp Owner</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ApiWhatsapp.update', $whatsappOwner->id) }}" method="POST" id="editForm">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Nama Owner <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           placeholder="Masukkan Nama Owner Whatsapp"
                                           value="{{ old('name', $whatsappOwner->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>No Whatsapp <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('whatsapp_number') is-invalid @enderror"
                                           name="whatsapp_number"
                                           placeholder="Masukkan Nomor Whatsapp"
                                           value="{{ old('whatsapp_number', $whatsappOwner->whatsapp_number) }}"
                                           required>
                                    @error('whatsapp_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Quota <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number"
                                            class="form-control @error('quota') is-invalid @enderror"
                                            name="quota"
                                            placeholder="0"
                                            min="0"
                                            max="50000"
                                            value="{{ old('quota', $whatsappOwner->quota) }}"
                                            required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-info"
                                                    type="button"
                                                    onclick="showQuotaTopUpHelper()"
                                                    title="Top Up Quota">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Quota saat ini: <strong>{{ number_format($whatsappOwner->quota) }}</strong> pesan.
                                        Gunakan tombol <i class="fas fa-plus"></i> untuk top up atau edit manual.
                                        <br>
                                        <strong>Catatan:</strong> Quota akan berkurang otomatis setiap pesan WhatsApp terkirim.
                                    </small>
                                    @error('quota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Berlangganan</label>
                                    <input type="date"
                                           class="form-control @error('subscription_date') is-invalid @enderror"
                                           name="subscription_date"
                                           value="{{ old('subscription_date', $whatsappOwner->subscription_date ? $whatsappOwner->subscription_date->format('Y-m-d') : '') }}">
                                    @error('subscription_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Token API <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control @error('token') is-invalid @enderror"
                                               name="token"
                                               id="token"
                                               placeholder="Token will be generated automatically"
                                               value="{{ old('token', $whatsappOwner->token) }}"
                                               required>
                                        {{-- <div class="input-group-append">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    id="generateToken"
                                                    title="Generate Random Token">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div> --}}
                                    </div>
                                    @error('token')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="status_switch"
                                               {{ old('status', $whatsappOwner->status) == 'active' ? 'checked' : '' }}>
                                        <input type="hidden" name="status" id="status_input" value="{{ old('status', $whatsappOwner->status) }}">
                                        <label class="custom-control-label" for="status_switch">Status Aktif</label>
                                    </div>
                                </div>

                                <div class="card-footer text-right border-0 pt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('ApiWhatsapp.index') }}" class="btn btn-secondary">
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
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            // Generate random token
            // $('#generateToken').click(function() {
            //     const randomToken = generateRandomToken(32);
            //     $('#token').val(randomToken);
            // });

            // Function to generate random token
            function generateRandomToken(length) {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';

                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }

                return result;
            }

            // Handle status toggle switch
            $('#status_switch').change(function() {
                if($(this).is(':checked')) {
                    $('#status_input').val('active');
                } else {
                    $('#status_input').val('inactive');
                }
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = $('#saveBtn');

                // Reset form state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

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
                                window.location.href = "{{ route('ApiWhatsapp.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html('<i class="fas fa-save"></i> Simpan').prop('disabled', false);

                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                $(`[name="${key}"]`)
                                    .addClass('is-invalid')
                                    .after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan data.',
                                'error'
                            );
                        }
                    }
                });
            });
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
