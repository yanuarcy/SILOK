@extends('Template.template')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        .section-title {
            color: #6777ef;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }

        .pagination-wrapper {
            margin: 30px 0;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper .pagination-container {
            background: #fff;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 4px;
        }

        .pagination-wrapper .page-btn {
            display: inline-block;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            color: #6c757d;
            background: transparent;
            text-decoration: none;
            transition: all 0.2s ease;
            min-width: 40px;
            text-align: center;
            cursor: pointer;
        }

        .pagination-wrapper .page-btn:hover {
            background: #6777ef;
            color: #fff;
            text-decoration: none;
        }

        .pagination-wrapper .page-btn.active {
            background: #6777ef;
            color: #fff;
            font-weight: 500;
        }

        .pagination-wrapper .page-btn.disabled {
            color: #dee2e6;
            cursor: not-allowed;
        }

        .pagination-wrapper .page-btn.disabled:hover {
            background: transparent;
            color: #dee2e6;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .pagination-wrapper .page-btn {
                padding: 6px 10px;
                font-size: 14px;
                min-width: 36px;
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
                <h1>Activities</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('Dashboard.General') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Activities</div>
                </div>
            </div>

            <div class="section-body">
                <!-- Filter Section -->
                <div class="filter-section">
                    <form method="GET" action="{{ route('activities.index') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ $currentDate ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Items per Page</label>
                            <select name="per_page" class="form-control" style="padding: 5px">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('activities.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
                @if($activities->count() > 0)
                    @foreach($groupedActivities as $date => $dateActivities)
                            @php
                                $carbonDate = \Carbon\Carbon::parse($date);
                                $today = \Carbon\Carbon::today();
                                $yesterday = \Carbon\Carbon::yesterday();

                                if ($carbonDate->isSameDay($today)) {
                                    $dateLabel = 'Hari Ini';
                                } elseif ($carbonDate->isSameDay($yesterday)) {
                                    $dateLabel = 'Kemarin';
                                } else {
                                    $dateLabel = $carbonDate->isoFormat('dddd, D MMMM Y');
                                }
                            @endphp
                        <h2 class="section-title">{{ $dateLabel }}</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="activities">
                                    @foreach($dateActivities as $activity)
                                        <div class="activity">
                                            <div class="activity-icon {{ $activity->color ?? 'bg-primary' }} shadow-primary text-white">
                                                <i class="{{ $activity->icon }}"></i>
                                            </div>
                                            <div class="activity-detail">
                                                <div class="mb-2">
                                                    <span class="text-job text-primary">{{ $activity->created_at->format('H:i') }}</span>
                                                    <span class="bullet"></span>
                                                    @if($activity->getSubjectLink())
                                                        <a href="{{ $activity->getSubjectLink() }}" class="text-job">View</a>
                                                    @else
                                                        <span class="text-job">{{ $activity->time_ago }}</span>
                                                    @endif
                                                    <div class="dropdown float-right">
                                                        <a href="#"
                                                            data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                                        <div class="dropdown-menu">
                                                            <div class="dropdown-title">Options</div>
                                                            <button type="button" class="dropdown-item has-icon view-detail" data-id="{{ $activity->id }}">
                                                                <i class="fas fa-list"></i> Detail
                                                            </button>
                                                            @if($activity->getSubjectLink())
                                                                <a href="{{ $activity->getSubjectLink() }}" class="dropdown-item has-icon">
                                                                    <i class="fas fa-external-link-alt"></i> Go to Data
                                                                </a>
                                                            @endif
                                                            @if(in_array(Auth::user()->role, ['admin']))
                                                                <div class="dropdown-divider"></div>
                                                                <button type="button" class="dropdown-item has-icon text-danger delete-activity"
                                                                        data-id="{{ $activity->id }}"
                                                                        data-confirm="Wait, wait, wait...|This action can't be undone. Want to take risks?"
                                                                        data-confirm-text-yes="Yes, IDC">
                                                                    <i class="fas fa-trash-alt"></i> Archive
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="activity-description">
                                                    @if($activity->getSubjectLink())
                                                        {!! preg_replace('/("[^"]*")/', '<a href="'.$activity->getSubjectLink().'">$1</a>', $activity->description) !!}
                                                    @else
                                                        {{ $activity->description }}
                                                    @endif

                                                    @if($activity->user)
                                                        oleh <strong>{{ $activity->user->name }}</strong> ({{ $activity->user->role }})
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    {{-- <div class="pagination-wrapper">
                        {{ $activities->appends(request()->query())->links() }}
                    </div> --}}
                    @if($activities->hasPages())
                        <div class="pagination-wrapper">
                            <div class="pagination-container">
                                {{-- Previous Page Link --}}
                                @if ($activities->onFirstPage())
                                    <span class="page-btn disabled">Previous</span>
                                @else
                                    <a href="{{ $activities->appends(request()->query())->previousPageUrl() }}" class="page-btn">Previous</a>
                                @endif

                                {{-- Page Numbers --}}
                                @php
                                    $start = max($activities->currentPage() - 2, 1);
                                    $end = min($start + 4, $activities->lastPage());
                                    $start = max($end - 4, 1);
                                @endphp

                                @if($start > 1)
                                    <a href="{{ $activities->appends(request()->query())->url(1) }}" class="page-btn">1</a>
                                    @if($start > 2)
                                        <span class="page-btn disabled">...</span>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    @if ($i == $activities->currentPage())
                                        <span class="page-btn active">{{ $i }}</span>
                                    @else
                                        <a href="{{ $activities->appends(request()->query())->url($i) }}" class="page-btn">{{ $i }}</a>
                                    @endif
                                @endfor

                                @if($end < $activities->lastPage())
                                    @if($end < $activities->lastPage() - 1)
                                        <span class="page-btn disabled">...</span>
                                    @endif
                                    <a href="{{ $activities->appends(request()->query())->url($activities->lastPage()) }}" class="page-btn">{{ $activities->lastPage() }}</a>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($activities->hasMorePages())
                                    <a href="{{ $activities->appends(request()->query())->nextPageUrl() }}" class="page-btn">Next</a>
                                @else
                                    <span class="page-btn disabled">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="row">
                        <div class="col-12">
                            <div class="empty-activities">
                                <i class="fas fa-inbox"></i>
                                <h5>Tidak Ada Aktivitas</h5>
                                <p class="text-muted">Belum ada aktivitas yang dapat ditampilkan dengan filter yang dipilih.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Activity Detail Modal -->
    <div class="modal fade" id="activityDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Aktivitas</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="activityDetailContent">
                    <!-- Content will be loaded here -->
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="footer-left">
            Copyright &copy; 2018 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad Nauval Azhar</a>
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
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // View activity detail
            $(document).on('click', '.view-detail', function() {
                const activityId = $(this).data('id');

                $('#activityDetailModal').modal('show');
                $('#activityDetailContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');

                $.ajax({
                    url: `/Activities/${activityId}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log('Response received:', response);
                        if (response.success) {
                            const activity = response.data;
                            let content = `
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="activity-icon ${activity.color || 'bg-primary'} text-white mr-3"
                                                style="width: 50px; height: 50px; font-size: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                <i class="${activity.icon}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">${activity.description}</h6>
                                                <small class="text-muted">${activity.time_ago}</small>
                                            </div>
                                        </div>

                                        <div class="activity-details">
                                            <p><strong>Pengguna:</strong> ${activity.user.name} (${activity.user.role})</p>
                                            <p><strong>Waktu:</strong> ${activity.created_at}</p>
                                            <p><strong>IP Address:</strong> ${activity.ip_address || 'N/A'}</p>
                                            ${activity.subject_link ? `<p><strong>Link:</strong> <a href="${activity.subject_link}" target="_blank" class="btn btn-sm btn-primary">Lihat Data</a></p>` : ''}
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <img src="{{ asset('${activity.user.avatar}') }}" class="rounded-circle" width="80" height="80" alt="User Avatar"
                                            onerror="this.src='{{ asset('img/avatar/avatar-1.png') }}'">
                                        <div class="mt-2">
                                            <strong>${activity.user.name}</strong><br>
                                            <small class="text-muted">${activity.user.role}</small>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // PERBAIKAN: Tampilkan analisis user-friendly, bukan JSON
                            if (activity.analysis) {
                                content += `
                                    <hr>
                                    <h6><i class="fas fa-chart-line text-primary"></i> Analisis Aktivitas</h6>
                                    <div class="card border-light">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h6 class="text-primary">Ringkasan</h6>
                                                <p class="mb-2">${activity.analysis.summary}</p>
                                            </div>
                                `;

                                // Detail analisis
                                if (activity.analysis.details && Object.keys(activity.analysis.details).length > 0) {
                                    content += `
                                        <div class="mb-3">
                                            <h6 class="text-info">Detail Perubahan</h6>
                                            <div class="row">
                                    `;

                                    Object.entries(activity.analysis.details).forEach(([key, value]) => {
                                        content += `
                                            <div class="col-md-6 mb-2">
                                                <strong>${key}:</strong><br>
                                                <span class="text-muted">${value}</span>
                                            </div>
                                        `;
                                    });

                                    content += `</div></div>`;
                                }

                                // Impact
                                if (activity.analysis.impact) {
                                    content += `
                                        <div class="mb-3">
                                            <h6 class="text-warning">Dampak</h6>
                                            <p class="mb-2">${activity.analysis.impact}</p>
                                        </div>
                                    `;
                                }

                                // Next steps
                                if (activity.analysis.next_steps && activity.analysis.next_steps.length > 0) {
                                    content += `
                                        <div class="mb-3">
                                            <h6 class="text-success">Langkah Selanjutnya</h6>
                                            <ul class="list-unstyled">
                                    `;

                                    activity.analysis.next_steps.forEach(step => {
                                        content += `<li><i class="fas fa-arrow-right text-success mr-2"></i>${step}</li>`;
                                    });

                                    content += `</ul></div>`;
                                }

                                content += `</div></div>`;
                            }

                            // Hanya tampilkan raw properties untuk admin/debugging (optional)
                            if (activity.properties && Object.keys(activity.properties).length > 0 &&
                                ['admin', 'Super Admin'].includes('{{ Auth::user()->role }}')) {
                                content += `
                                    <hr>
                                    <div class="mt-3">
                                        <h6 class="text-muted">
                                            <i class="fas fa-code"></i> Technical Properties
                                            <small>(Admin Only)</small>
                                        </h6>
                                        <div class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                                            <pre style="margin: 0; font-size: 11px;">${JSON.stringify(activity.properties, null, 2)}</pre>
                                        </div>
                                    </div>
                                `;
                            }

                            $('#activityDetailContent').html(content);
                        } else {
                            $('#activityDetailContent').html('<div class="alert alert-danger">Data tidak ditemukan</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr.responseText);
                        console.log('Status:', status);
                        console.log('Error:', error);

                        let errorMessage = 'Error loading activity details';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Activity not found';
                        } else if (xhr.status === 403) {
                            errorMessage = 'Unauthorized access';
                        }

                        $('#activityDetailContent').html(`<div class="alert alert-danger">${errorMessage}</div>`);
                    }
                });
            });

            // Delete activity with confirmation - tetap sama
            $(document).on('click', '.delete-activity', function() {
                const activityId = $(this).data('id');

                Swal.fire({
                    title: 'Archive Activity?',
                    text: 'This action cannot be undone. Want to take risks?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, IDC',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/Activities/${activityId}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Archived!', response.message, 'success')
                                        .then(() => location.reload());
                                }
                            },
                            error: function(xhr) {
                                console.log('Delete Error:', xhr.responseText);
                                Swal.fire('Error!', 'Failed to archive activity', 'error');
                            }
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
