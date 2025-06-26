@extends('layouts.app')

@section('title', $perpu->full_title)

@push('styles')
<style>
    .pdf-viewer-container {
        background: #2c3e50;
        min-height: 100vh;
        padding: 0;
    }

    .pdf-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .pdf-title {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .pdf-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    .pdf-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-control {
        padding: 8px 16px;
        border-radius: 25px;
        border: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
    }

    .btn-download {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .pdf-embed {
        width: 100%;
        height: calc(100vh - 80px);
        border: none;
    }

    .pdf-info {
        background: white;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .info-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #6777ef;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-value {
        color: #2c3e50;
        font-weight: 500;
    }

    .pdf-description {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        margin-top: 15px;
    }

    .tags-container {
        margin-top: 15px;
    }

    .tag-item {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 4px 10px;
        margin: 2px;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    .stats-item {
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stats-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: #6777ef;
        display: block;
    }

    .stats-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }

    .mobile-controls {
        display: none;
        background: white;
        padding: 10px;
        border-top: 1px solid #e9ecef;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .pdf-header {
            padding: 10px 0;
        }

        .pdf-title {
            font-size: 1rem;
        }

        .pdf-controls {
            display: none;
        }

        .mobile-controls {
            display: flex;
            justify-content: space-around;
        }

        .pdf-embed {
            height: calc(100vh - 120px);
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(44, 62, 80, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 999;
    }

    .loading-spinner {
        text-align: center;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="pdf-viewer-container">
    <!-- PDF Header -->
    <div class="pdf-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="pdf-title">{{ $perpu->full_title }}</h1>
                    <p class="pdf-subtitle">{{ $perpu->tentang }}</p>
                </div>
                <div class="col-md-4">
                    <div class="pdf-controls justify-content-end">
                        <a href="{{ route('perpu.index') }}" class="btn-control btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('perpu.download', $perpu->id) }}" class="btn-control btn-download">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Info (Optional, can be toggled) -->
    <div class="pdf-info" id="pdfInfo" style="display: none;">
        <div class="container-fluid">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Jenis Peraturan</div>
                    <div class="info-value">{{ $perpu->jenis_peraturan }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nomor & Tahun</div>
                    <div class="info-value">{{ $perpu->nomor_peraturan }} / {{ $perpu->tahun }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Penetapan</div>
                    <div class="info-value">{{ $perpu->tanggal_penetapan->format('d F Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Ukuran File</div>
                    <div class="info-value">{{ $perpu->formatted_file_size }}</div>
                </div>
                <div class="stats-item">
                    <span class="stats-number">{{ number_format($perpu->download_count) }}</span>
                    <span class="stats-label">Downloads</span>
                </div>
            </div>

            @if($perpu->deskripsi)
                <div class="pdf-description">
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value">{{ $perpu->deskripsi }}</div>
                </div>
            @endif

            @if($perpu->tags && count($perpu->tags) > 0)
                <div class="tags-container">
                    <div class="info-label">Tags</div>
                    @foreach($perpu->tags as $tag)
                        <span class="tag-item"># {{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <div>Memuat dokumen PDF...</div>
        </div>
    </div>

    <!-- PDF Embed -->
    <embed src="{{ $perpu->file_url }}#toolbar=1&navpanes=1&scrollbar=1"
           type="application/pdf"
           class="pdf-embed"
           id="pdfEmbed">

    <!-- Mobile Controls -->
    <div class="mobile-controls">
        <a href="{{ route('perpu.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-info btn-sm" onclick="toggleInfo()">
            <i class="bi bi-info-circle"></i> Info
        </button>
        <a href="{{ route('perpu.download', $perpu->id) }}" class="btn btn-success btn-sm">
            <i class="bi bi-download"></i> Download
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Hide loading overlay when PDF is loaded
    $('#pdfEmbed').on('load', function() {
        $('#loadingOverlay').fadeOut();
    });

    // Show loading overlay initially
    setTimeout(function() {
        if ($('#loadingOverlay').is(':visible')) {
            $('#loadingOverlay').fadeOut();
        }
    }, 5000); // Hide after 5 seconds max

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // ESC to go back
        if (e.keyCode === 27) {
            window.location.href = "{{ route('perpu.index') }}";
        }
        // Ctrl+D to download
        if (e.ctrlKey && e.keyCode === 68) {
            e.preventDefault();
            window.location.href = "{{ route('perpu.download', $perpu->id) }}";
        }
        // I to toggle info
        if (e.keyCode === 73) {
            toggleInfo();
        }
    });

    // Auto-hide info panel on desktop after 3 seconds
    if ($(window).width() > 768) {
        setTimeout(function() {
            $('#pdfInfo').slideUp();
        }, 3000);
    }
});

function toggleInfo() {
    $('#pdfInfo').slideToggle();
}

// Track PDF view (optional analytics)
function trackPdfView() {
    $.ajax({
        url: "{{ route('admin.Perpu.track-view', $perpu->id) }}", // You can create this route
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            action: 'view'
        }
    });
}

// Call track function after 5 seconds (assuming user is actually viewing)
setTimeout(trackPdfView, 5000);
</script>
@endpush
