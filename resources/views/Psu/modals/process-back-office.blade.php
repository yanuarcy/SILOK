{{-- resources/views/Psu/modals/process-back-office.blade.php --}}
<!-- Modal Process Back Office -->
<div class="modal fade" id="processBackOfficeModal" tabindex="-1" role="dialog" aria-labelledby="processBackOfficeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="processBackOfficeModalLabel">
                    <i class="fas fa-file-export"></i> Buat E-Surat Final
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="processBackOfficeForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Pembuatan E-Surat Final</strong><br>
                        Proses ini akan membuat E-Surat resmi dari Kelurahan yang akan dikirim ke pemohon dan mengubah status menjadi "Selesai".
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nomor Surat Asli</strong></label>
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
                                <label for="nomorNotaDinas">Nomor Nota Dinas <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       name="nomor_nota_dinas"
                                       id="nomorNotaDinas"
                                       placeholder="Contoh: 470/001/436.9.04/2025"
                                       required>
                                <small class="form-text text-muted">
                                    Format: [nomor]/[kode]/[lokasi]/[tahun]
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Tanggal E-Surat</strong></label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ now()->format('d F Y') }}"
                                       readonly>
                                <small class="form-text text-muted">Tanggal pembuatan E-Surat</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatanBackOffice">Catatan Back Office (Opsional)</label>
                        <textarea class="form-control"
                                  name="catatan_back_office"
                                  id="catatanBackOffice"
                                  rows="3"
                                  placeholder="Catatan tambahan untuk proses pembuatan E-Surat..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan tersimpan dalam sistem untuk keperluan administratif
                        </small>
                    </div>

                    <!-- Preview Section -->
                    <div class="form-group">
                        <label><strong>Preview E-Surat</strong></label>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-2"><strong>Yang akan dibuat:</strong></p>
                            <ul class="mb-0">
                                <li>E-Surat resmi dari Kelurahan dengan TTD dan Stempel Lurah</li>
                                <li>Nomor Nota Dinas yang unik untuk tracking</li>
                                <li>Referensi ke surat permohonan asli</li>
                                <li>Status permohonan berubah menjadi "Selesai"</li>
                                <li>Notifikasi akan dikirim ke pemohon</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmBackOffice" required>
                            <label class="custom-control-label" for="confirmBackOffice">
                                Saya konfirmasi telah memeriksa data dan siap membuat E-Surat final
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirmNotification" checked>
                            <label class="custom-control-label" for="confirmNotification">
                                Kirim notifikasi ke pemohon bahwa E-Surat sudah selesai
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-info" id="processBackOfficeBtn">
                        <i class="fas fa-file-export"></i> Buat E-Surat Final
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle modal show event
    $('#processBackOfficeModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var psuId = button.data('id');
        var psuName = button.data('name');

        // Update modal title
        $('#processBackOfficeModalLabel').text('Buat E-Surat Final - ' + psuName);

        // Reset form
        $('#processBackOfficeForm')[0].reset();
        $('#confirmBackOffice').prop('checked', false);
        $('#confirmNotification').prop('checked', true);

        // Store PSU ID in form
        $('#processBackOfficeForm').data('id', psuId);

        // Load PSU details
        loadPsuDetailsForBackOffice(psuId);
    });

    // Form submit handler
    $('#processBackOfficeForm').on('submit', function(e) {
        e.preventDefault();

        var psuId = $(this).data('id');
        var formData = new FormData(this);

        // Validation
        if (!$('#confirmBackOffice').is(':checked')) {
            Swal.fire('Error', 'Anda harus mengkonfirmasi pembuatan E-Surat', 'error');
            return;
        }

        // Submit
        $.ajax({
            url: `/psu/${psuId}/process-back-office`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#processBackOfficeBtn').html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);
            },
            success: function(response) {
                if(response.success) {
                    $('#processBackOfficeModal').modal('hide');

                    // Reload table if exists
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    }

                    // Reload summary if exists
                    if (typeof loadSummaryData === 'function') {
                        loadSummaryData();
                    }

                    Swal.fire('Berhasil!', response.message, 'success');
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.';
                Swal.fire('Error!', message, 'error');
            },
            complete: function() {
                $('#processBackOfficeBtn').html('<i class="fas fa-file-export"></i> Buat E-Surat Final').prop('disabled', false);
            }
        });
    });
});

function loadPsuDetailsForBackOffice(psuId) {
    $.ajax({
        url: `/psu/${psuId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                var psu = response.data;

                // Fill form fields
                $('#backOfficeNomorSurat').val(psu.nomor_surat || '');
                $('#backOfficeNamaPemohon').val(psu.nama_lengkap || '');
                $('#backOfficeHal').val(psu.hal || '');

                // Generate nomor nota dinas suggestion
                var today = new Date();
                var year = today.getFullYear();
                var month = String(today.getMonth() + 1).padStart(2, '0');
                var sequence = String(Math.floor(Math.random() * 999) + 1).padStart(3, '0');
                var suggestedNomor = `470/${sequence}/436.9.04/${year}`;

                $('#nomorNotaDinas').attr('placeholder', suggestedNomor);
            }
        },
        error: function(xhr) {
            console.error('Error loading PSU details:', xhr);
            Swal.fire('Error', 'Gagal memuat detail PSU', 'error');
        }
    });
}
</script>
