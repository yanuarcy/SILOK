{{-- resources/views/Psu/modals/approve-rt.blade.php --}}
<!-- Modal Approve RT -->
<div class="modal fade" id="approveRTModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveRTModalLabel">Approve Permohonan RT</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveRTForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan RT</label>
                                <div id="ttd-rt-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat TTD RT...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel RT</label>
                                <div id="stempel-rt-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat Stempel RT...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan_rt" rows="3" placeholder="Catatan untuk persetujuan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/Psu/modals/reject-rt.blade.php --}}
<!-- Modal Reject RT -->
<div class="modal fade" id="rejectRTModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectRTModalLabel">Reject Permohonan RT</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectRTForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan_rt" class="form-control" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/Psu/modals/approve-rw.blade.php --}}
<!-- Modal Approve RW -->
<div class="modal fade" id="approveRWModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveRWModalLabel">Approve Permohonan RW</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveRWForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan RW</label>
                                <div id="ttd-rw-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat TTD RW...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel RW</label>
                                <div id="stempel-rw-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat Stempel RW...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan_rw" rows="3" placeholder="Catatan untuk persetujuan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/Psu/modals/reject-rw.blade.php --}}
<!-- Modal Reject RW -->
<div class="modal fade" id="rejectRWModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectRWModalLabel">Reject Permohonan RW</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectRWForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan_rw" class="form-control" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/Psu/partials/modal.blade.php --}}
{{-- File ini untuk modal umum yang bisa digunakan di berbagai view --}}

<!-- Include modals berdasarkan role yang login -->
@if(Auth::check())
    @if(Auth::user()->role === 'Ketua RT')
        @include('Psu.modals.approve-rt')
        @include('Psu.modals.reject-rt')
    @endif

    @if(Auth::user()->role === 'Ketua RW')
        @include('Psu.modals.approve-rw')
        @include('Psu.modals.reject-rw')
    @endif

    {{-- Tambahkan modal lain sesuai kebutuhan --}}
@endif
