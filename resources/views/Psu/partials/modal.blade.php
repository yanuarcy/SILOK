{{-- resources/views/Psu/partials/modal.blade.php --}}

<!-- ========================================== -->
<!-- MODAL APPROVE RT -->
<!-- ========================================== -->
<div class="modal fade" id="approveRTModal" tabindex="-1" role="dialog" aria-labelledby="approveRTModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveRTModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Setujui PSU - RT
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveRTForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi:</strong> TTD dan Stempel akan diambil otomatis dari data spesimen RT yang sudah tersimpan.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan Digital RT: <span class="text-danger">*</span></label>
                                <div id="ttd-rt-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen TTD RT...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel RT: <span class="text-danger">*</span></label>
                                <div id="stempel-rt-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen stempel RT...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan_rt">Catatan Persetujuan (Opsional):</label>
                        <textarea class="form-control" id="catatan_rt" name="catatan_rt" rows="3" placeholder="Tambahkan catatan persetujuan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i>Setujui sebagai RT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL REJECT RT -->
<!-- ========================================== -->
<div class="modal fade" id="rejectRTModal" tabindex="-1" role="dialog" aria-labelledby="rejectRTModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectRTModalLabel">
                    <i class="fas fa-times-circle mr-2"></i>Tolak PSU - RT
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectRTForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Penolakan ini akan menghentikan proses persetujuan dan memberitahu pemohon.
                    </div>

                    <div class="form-group">
                        <label for="catatan_rt_reject">Alasan Penolakan: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_rt_reject" name="catatan_rt" rows="4" placeholder="Jelaskan dengan detail alasan penolakan..." required></textarea>
                        <small class="form-text text-muted">Alasan ini akan dikirimkan kepada pemohon sebagai feedback</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-2"></i>Tolak sebagai RT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL APPROVE RW -->
<!-- ========================================== -->
<div class="modal fade" id="approveRWModal" tabindex="-1" role="dialog" aria-labelledby="approveRWModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveRWModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Setujui PSU - RW
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveRWForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi:</strong> TTD dan Stempel akan diambil otomatis dari data spesimen RW yang sudah tersimpan.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan Digital RW: <span class="text-danger">*</span></label>
                                <div id="ttd-rw-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen TTD RW...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel RW: <span class="text-danger">*</span></label>
                                <div id="stempel-rw-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen stempel RW...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan_rw">Catatan Persetujuan (Opsional):</label>
                        <textarea class="form-control" id="catatan_rw" name="catatan_rw" rows="3" placeholder="Tambahkan catatan persetujuan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i>Setujui sebagai RW
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL REJECT RW -->
<!-- ========================================== -->
<div class="modal fade" id="rejectRWModal" tabindex="-1" role="dialog" aria-labelledby="rejectRWModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectRWModalLabel">
                    <i class="fas fa-times-circle mr-2"></i>Tolak PSU - RW
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectRWForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Penolakan ini akan menghentikan proses persetujuan dan memberitahu pemohon.
                    </div>

                    <div class="form-group">
                        <label for="catatan_rw_reject">Alasan Penolakan: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_rw_reject" name="catatan_rw" rows="4" placeholder="Jelaskan dengan detail alasan penolakan..." required></textarea>
                        <small class="form-text text-muted">Alasan ini akan dikirimkan kepada pemohon sebagai feedback</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-2"></i>Tolak sebagai RW
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL RECEIVE KELURAHAN (Front Office) -->
<!-- ========================================== -->
<div class="modal fade" id="receiveKelurahanModal" tabindex="-1" role="dialog" aria-labelledby="receiveKelurahanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="receiveKelurahanModalLabel">
                    <i class="fas fa-inbox mr-2"></i>Terima PSU di Kelurahan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="receiveKelurahanForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Proses Penerimaan di Kelurahan</strong><br>
                        Dengan menerima PSU ini, sistem akan otomatis membuat:
                        <ul class="mb-0 mt-2">
                            <li>Surat Tanda Terima untuk pemohon</li>
                            <li>Lembar Disposisi untuk Lurah</li>
                        </ul>
                        TTD dan Stempel akan diambil otomatis dari data spesimen Front Office yang sudah tersimpan.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nomor Surat</strong></label>
                                <input type="text" class="form-control" id="receiveNomorSurat" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nama Pemohon</strong></label>
                                <input type="text" class="form-control" id="receiveNamaPemohon" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Perihal</strong></label>
                        <textarea class="form-control" id="receiveHal" rows="2" readonly></textarea>
                    </div>

                    <!-- TTD dan Stempel Front Office -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan Digital Front Office: <span class="text-danger">*</span></label>
                                <div id="ttd-front-office-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen TTD Front Office...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel Kelurahan: <span class="text-danger">*</span></label>
                                <div id="stempel-kelurahan-receive-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat stempel Kelurahan...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatanFrontOffice">Catatan Front Office (Opsional)</label>
                        <textarea class="form-control"
                                  name="catatan_front_office"
                                  id="catatanFrontOffice"
                                  rows="3"
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan tercatat dalam sistem dan dapat dilihat oleh Lurah
                        </small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmReceive" required>
                            <label class="custom-control-label" for="confirmReceive">
                                Saya konfirmasi telah menerima dan memeriksa kelengkapan berkas PSU ini
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="receiveKelurahanBtn">
                        <i class="fas fa-inbox mr-2"></i>Terima di Kelurahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL PROCESS LURAH - FIXED VERSION -->
<!-- ========================================== -->
<div class="modal fade" id="processLurahModal" tabindex="-1" role="dialog" aria-labelledby="processLurahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="processLurahModalLabel">
                    <i class="fas fa-user-tie mr-2"></i>Proses Disposisi Lurah
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="processLurahForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Proses Disposisi Lurah</strong><br>
                        Silakan berikan instruksi/arahan dan tanda tangan digital untuk melanjutkan proses ke Back Office.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nomor Surat</strong></label>
                                <input type="text" class="form-control" id="processLurahNomorSurat" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nama Pemohon</strong></label>
                                <input type="text" class="form-control" id="processLurahNamaPemohon" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Perihal</strong></label>
                        <textarea class="form-control" id="processLurahHal" rows="2" readonly></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- PERBAIKAN: Instruksi/Arahan - Radio Button -->
                            <div class="form-group">
                                <label><strong>Instruksi/Arahan <span class="text-danger">*</span></strong></label>
                                <div class="border rounded p-3">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="instruksi1" name="instruksi_arahan" value="Setuju untuk diproses" required>
                                        <label class="custom-control-label" for="instruksi1">Setuju untuk diproses</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="instruksi2" name="instruksi_arahan" value="Perlu kajian lebih lanjut">
                                        <label class="custom-control-label" for="instruksi2">Perlu kajian lebih lanjut</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="instruksi3" name="instruksi_arahan" value="Koordinasi dengan bagian terkait">
                                        <label class="custom-control-label" for="instruksi3">Koordinasi dengan bagian terkait</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="instruksi4" name="instruksi_arahan" value="Mohon ditindaklanjuti">
                                        <label class="custom-control-label" for="instruksi4">Mohon ditindaklanjuti</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="instruksi5" name="instruksi_arahan" value="Lain-lain">
                                        <label class="custom-control-label" for="instruksi5">Lain-lain (sebutkan di catatan)</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Pilih salah satu instruksi yang sesuai</small>
                            </div>

                            <!-- Catatan Lurah -->
                            <div class="form-group">
                                <label for="catatanLurah">Catatan/Keterangan Lurah <span class="text-danger">*</span></label>
                                <textarea class="form-control"
                                          name="catatan_lurah"
                                          id="catatanLurah"
                                          rows="4"
                                          placeholder="Masukkan catatan, keterangan, atau instruksi tambahan..."
                                          required></textarea>
                                <small class="form-text text-muted">
                                    Catatan ini akan menjadi bagian dari lembar disposisi yang ditandatangani
                                </small>
                            </div>

                            <!-- Diteruskan Kepada -->
                            {{-- <div class="form-group">
                                <label><strong>Diteruskan Kepada</strong></label>
                                <div class="border rounded p-3">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="teruskan1" name="diteruskan_kepada" value="Back Office" checked>
                                        <label class="custom-control-label" for="teruskan1">Back Office untuk approval final</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="teruskan2" name="diteruskan_kepada" value="Sekretariat">
                                        <label class="custom-control-label" for="teruskan2">Sekretariat untuk arsip</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="teruskan3" name="diteruskan_kepada" value="Bagian lain">
                                        <label class="custom-control-label" for="teruskan3">Bagian lain</label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <div class="col-md-6">
                            <!-- PERBAIKAN: TTD dan Stempel Lurah menggunakan spesimen -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanda Tangan Digital Lurah <span class="text-danger">*</span></label>
                                        <div id="ttd-lurah-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                                <br>Memuat spesimen TTD Lurah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stempel Kelurahan <span class="text-danger">*</span></label>
                                        <div id="stempel-lurah-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                                <br>Memuat stempel Kelurahan...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><strong>Diteruskan Kepada</strong></label>
                                <div class="border rounded p-3">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="teruskan1" name="diteruskan_kepada" value="Back Office" checked>
                                        <label class="custom-control-label" for="teruskan1">Back Office untuk approval final</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="teruskan2" name="diteruskan_kepada" value="Sekretariat">
                                        <label class="custom-control-label" for="teruskan2">Sekretariat untuk arsip</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="teruskan3" name="diteruskan_kepada" value="Bagian lain">
                                        <label class="custom-control-label" for="teruskan3">Bagian lain</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Signature Pad for Manual Signature -->
                            {{-- <div class="form-group">
                                <label>Tanda Tangan Manual (Opsional)</label>
                                <div class="border rounded p-2">
                                    <canvas id="signaturePadLurah"
                                            class="signature-pad"
                                            width="400"
                                            height="150"
                                            style="border: 1px dashed #ccc; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-sm btn-secondary" id="clearSignatureLurah">
                                            <i class="fas fa-eraser mr-1"></i>Hapus Tanda Tangan
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        Jika ada, tanda tangan manual akan menggantikan spesimen TTD Lurah
                                    </small>
                                </div>
                                <input type="hidden" name="ttd_lurah_manual" id="ttdLurahManualInput">
                            </div> --}}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmProcessLurah" required>
                            <label class="custom-control-label" for="confirmProcessLurah">
                                Saya konfirmasi telah memeriksa dan memberikan disposisi untuk PSU ini
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning" id="processLurahBtn">
                        <i class="fas fa-signature mr-2"></i>Proses & Tanda Tangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL APPROVE BACK OFFICE (Final Approval) -->
<!-- ========================================== -->
<div class="modal fade" id="approveBackOfficeModal" tabindex="-1" role="dialog" aria-labelledby="approveBackOfficeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveBackOfficeModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Approve Final - Back Office
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveBackOfficeForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Approve Final Back Office</strong><br>
                        Proses ini akan menyelesaikan seluruh workflow PSU dan mengubah status menjadi "Selesai".
                        <ul class="mb-0 mt-2">
                            <li>Pemohon sudah mendapat Tanda Terima</li>
                            <li>Disposisi Lurah sudah ditandatangani</li>
                            <li>Data PSU sudah lengkap dan siap disetujui</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nomor Surat</strong></label>
                                <input type="text" class="form-control" id="backOfficeNomorSurat" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nama Pemohon</strong></label>
                                <input type="text" class="form-control" id="backOfficeNamaPemohon" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Perihal</strong></label>
                        <textarea class="form-control" id="backOfficeHal" rows="2" readonly></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Status Tanda Terima</strong></label>
                                <div class="form-control-plaintext">
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i>Sudah Diterbitkan
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Status Disposisi Lurah</strong></label>
                                <div class="form-control-plaintext">
                                    <span class="badge badge-success">
                                        <i class="fas fa-signature mr-1"></i>Sudah Ditandatangani
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatanBackOffice">Catatan Back Office (Opsional)</label>
                        <textarea class="form-control"
                                  name="catatan_back_office"
                                  id="catatanBackOffice"
                                  rows="3"
                                  placeholder="Catatan tambahan untuk penyelesaian PSU ini..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan tersimpan dalam sistem untuk keperluan administratif
                        </small>
                    </div>

                    <!-- Ringkasan Proses -->
                    <div class="form-group">
                        <label><strong>Ringkasan Proses yang Sudah Dilakukan</strong></label>
                        <div class="border rounded p-3 bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>✅ Dokumen yang Sudah Dibuat:</strong></p>
                                    <ul class="mb-0">
                                        <li>Surat Tanda Terima untuk Pemohon</li>
                                        <li>Lembar Disposisi untuk Lurah</li>
                                        <li>Disposisi Lurah (sudah ditandatangani)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>🎯 Yang Akan Dilakukan:</strong></p>
                                    <ul class="mb-0">
                                        <li>Approve final seluruh berkas</li>
                                        <li>Update status menjadi "Selesai"</li>
                                        <li>Notifikasi completion ke pemohon</li>
                                        <li>Arsip ke sistem</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmBackOffice" required>
                            <label class="custom-control-label" for="confirmBackOffice">
                                Saya konfirmasi telah memeriksa seluruh berkas dan disposisi, siap untuk menyelesaikan PSU ini
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmNotification" checked>
                            <label class="custom-control-label" for="confirmNotification">
                                Kirim notifikasi completion ke pemohon
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="approveBackOfficeBtn">
                        <i class="fas fa-check-circle mr-2"></i>Approve & Selesaikan PSU
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL APPROVE KELURAHAN (Final Approval untuk PSU dengan level_akhir = kelurahan) -->
<!-- ========================================== -->
<div class="modal fade" id="approveKelurahanModal" tabindex="-1" role="dialog" aria-labelledby="approveKelurahanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveKelurahanModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Setujui PSU - Kelurahan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveKelurahanForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi:</strong> TTD dan Stempel akan diambil otomatis dari data spesimen Kelurahan yang sudah tersimpan.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanda Tangan Digital Kelurahan: <span class="text-danger">*</span></label>
                                <div id="ttd-kelurahan-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen TTD Kelurahan...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stempel Kelurahan: <span class="text-danger">*</span></label>
                                <div id="stempel-kelurahan-preview" class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Memuat spesimen stempel Kelurahan...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan_kelurahan">Catatan Persetujuan (Opsional):</label>
                        <textarea class="form-control" id="catatan_kelurahan" name="catatan_kelurahan" rows="3" placeholder="Tambahkan catatan persetujuan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i>Setujui sebagai Kelurahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL REJECT KELURAHAN -->
<!-- ========================================== -->
<div class="modal fade" id="rejectKelurahanModal" tabindex="-1" role="dialog" aria-labelledby="rejectKelurahanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectKelurahanModalLabel">
                    <i class="fas fa-times-circle mr-2"></i>Tolak PSU - Kelurahan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectKelurahanForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Penolakan ini akan menghentikan proses persetujuan dan memberitahu pemohon.
                    </div>

                    <div class="form-group">
                        <label for="catatan_kelurahan_reject">Alasan Penolakan: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_kelurahan_reject" name="catatan_kelurahan" rows="4" placeholder="Jelaskan dengan detail alasan penolakan..." required></textarea>
                        <small class="form-text text-muted">Alasan ini akan dikirimkan kepada pemohon sebagai feedback</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-2"></i>Tolak sebagai Kelurahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SIGNATURE PAD LIBRARY & SCRIPTS -->
<!-- ========================================== -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    let signaturePadLurah = null;
    let lurahSpecimenData = null;

    $(document).ready(function() {

        // ========================================
        // SIGNATURE PAD INITIALIZATION
        // ========================================
        // $('#processLurahModal').on('shown.bs.modal', function() {
        //     setTimeout(() => {
        //         initializeLurahSignaturePad();
        //     }, 100);
        // });

        function initializeLurahSignaturePad() {
            const canvas = document.getElementById('signaturePadLurah');
            if (canvas && !signaturePadLurah) {
                signaturePadLurah = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)',
                    velocityFilterWeight: 0.7,
                    minWidth: 0.5,
                    maxWidth: 2.5,
                    throttle: 16,
                    minPointDistance: 3,
                });

                // Clear signature button
                $('#clearSignatureLurah').on('click', function() {
                    if (signaturePadLurah) {
                        signaturePadLurah.clear();
                    }
                });
            }
        }

        // ========================================
        // INSTRUKSI CHECKBOX HANDLER
        // ========================================
        $(document).on('change', '.instruksi-checkbox', function() {
            updateCatatanLurah();
        });

        function updateCatatanLurah() {
            const selectedInstruksi = [];
            $('.instruksi-checkbox:checked').each(function() {
                selectedInstruksi.push($(this).val());
            });

            if (selectedInstruksi.length > 0) {
                const currentCatatan = $('#catatanLurah').val();
                const instruksiText = "INSTRUKSI:\n" + selectedInstruksi.map(item => "- " + item).join("\n");

                if (!currentCatatan.includes("INSTRUKSI:")) {
                    $('#catatanLurah').val(instruksiText + (currentCatatan ? "\n\nCATATAN TAMBAHAN:\n" + currentCatatan : ""));
                }
            }
        }

        // ========================================
        // LOAD PSU DETAILS FUNCTION
        // ========================================
        function loadPsuDetails(psuId, targetFields) {
            $.ajax({
                url: `/psu/${psuId}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response && response.nomor_surat) {
                        if (targetFields.nomorSurat) $(targetFields.nomorSurat).val(response.nomor_surat);
                        if (targetFields.namaPemohon) $(targetFields.namaPemohon).val(response.nama_lengkap);
                        if (targetFields.hal) $(targetFields.hal).val(response.hal);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading PSU details:', xhr);
                }
            });
        }

        // ========================================
        // MODAL SHOW EVENT HANDLERS
        // ========================================
        $('#receiveKelurahanModal').on('show.bs.modal', function(event) {
            const psuId = $(this).data('psu-id');
            if (psuId) {
                loadPsuDetails(psuId, {
                    nomorSurat: '#receiveNomorSurat',
                    namaPemohon: '#receiveNamaPemohon',
                    hal: '#receiveHal'
                });
            }
        });

        $('#processLurahModal').on('show.bs.modal', function(event) {
            const psuId = $(this).data('psu-id');
            if (psuId) {
                loadPsuDetails(psuId, {
                    nomorSurat: '#processLurahNomorSurat',
                    namaPemohon: '#processLurahNamaPemohon',
                    hal: '#processLurahHal'
                });

                setTimeout(function() {
                    initializeLurahSignaturePad();
                }, 300);
            }
        });

        $('#approveBackOfficeModal').on('show.bs.modal', function(event) {
            const psuId = $(this).data('psu-id');
            if (psuId) {
                loadPsuDetails(psuId, {
                    nomorSurat: '#backOfficeNomorSurat',
                    namaPemohon: '#backOfficeNamaPemohon',
                    hal: '#backOfficeHal'
                });
            }
        });

        // ========================================
        // MODAL RESET WHEN CLOSED
        // ========================================
        $('.modal').on('hidden.bs.modal', function() {
            const modalId = $(this).attr('id');

            // Reset forms
            $(this).find('form')[0]?.reset();

            // Reset specific elements based on modal
            switch(modalId) {
                case 'approveRTModal':
                    $('#ttd-rt-preview, #stempel-rt-preview').html(`
                        <div class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                            <br>Memuat spesimen...
                        </div>
                    `);
                    break;
                case 'approveRWModal':
                    $('#ttd-rw-preview, #stempel-rw-preview').html(`
                        <div class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                            <br>Memuat spesimen...
                        </div>
                    `);
                    break;
                case 'approveKelurahanModal':
                    $('#ttd-kelurahan-preview, #stempel-kelurahan-preview').html(`
                        <div class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                            <br>Memuat spesimen...
                        </div>
                    `);
                    break;
                case 'processLurahModal':
                    if (signaturePadLurah) {
                        signaturePadLurah.clear();
                    }
                    $('.instruksi-checkbox').prop('checked', false);
                    break;
            }
        });

        // ========================================
        // FORM VALIDATION FOR LURAH PROCESS
        // ========================================
        $('#processLurahForm').on('submit', function(e) {
            // Additional validation for signature
            if (!signaturePadLurah || signaturePadLurah.isEmpty()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanda Tangan Diperlukan',
                    text: 'Harap buat tanda tangan digital sebelum melanjutkan.'
                });
                return false;
            }

            // Set signature data
            $('#ttdLurahInput').val(signaturePadLurah.toDataURL());
        });

    });

    // ========================================
    // GLOBAL FUNCTIONS (for external scripts if needed)
    // ========================================
    window.showReceiveKelurahanModal = function(id, name) {
        $('#receiveKelurahanModal').data('psu-id', id).modal('show');
        $('#receiveKelurahanForm').data('id', id);
    };

    window.showProcessLurahModal = function(id, name) {
        $('#processLurahModal').data('psu-id', id).modal('show');
        $('#processLurahForm').data('id', id);
    };

    window.showApproveBackOfficeModal = function(id, name) {
        $('#approveBackOfficeModal').data('psu-id', id).modal('show');
        $('#approveBackOfficeForm').data('id', id);
    };
</script>
