{{-- Partials file: resources/views/SuratPengantar/partials/modals.blade.php --}}

<!-- Modal for Approve RT -->
<div class="modal fade" id="approveRTModal" tabindex="-1" role="dialog" aria-labelledby="approveRTModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveRTModalLabel">Setujui Surat Pengantar - RT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveRTForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan Digital RT: <span class="text-danger">*</span></label>
                                <div id="ttd-rt-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat TTD RT...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel RT: <span class="text-danger">*</span></label>
                                <div id="stempel-rt-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat Stempel RT...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional):</label>
                        <textarea class="form-control" name="catatan_rt" rows="3" placeholder="Catatan persetujuan RT..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui sebagai RT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Approve RW -->
<div class="modal fade" id="approveRWModal" tabindex="-1" role="dialog" aria-labelledby="approveRWModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveRWModalLabel">Setujui Surat Pengantar - RW</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveRWForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan Digital RW: <span class="text-danger">*</span></label>
                                <div id="ttd-rw-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat TTD RW...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel RW: <span class="text-danger">*</span></label>
                                <div id="stempel-rw-preview" class="signature-display" style="min-height: 200px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Memuat Stempel RW...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional):</label>
                        <textarea class="form-control" name="catatan_rw" rows="3" placeholder="Catatan persetujuan RW..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui sebagai RW
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Reject RT -->
<div class="modal fade" id="rejectRTModal" tabindex="-1" role="dialog" aria-labelledby="rejectRTModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectRTModalLabel">Tolak Surat Pengantar - RT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectRTForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan RT: <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="catatan_rt" rows="4" placeholder="Masukkan alasan penolakan sebagai RT..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Tolak sebagai RT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Reject RW -->
<div class="modal fade" id="rejectRWModal" tabindex="-1" role="dialog" aria-labelledby="rejectRWModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectRWModalLabel">Tolak Surat Pengantar - RW</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectRWForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan RW: <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="catatan_rw" rows="4" placeholder="Masukkan alasan penolakan sebagai RW..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Tolak sebagai RW
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
