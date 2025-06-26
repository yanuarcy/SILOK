{{-- resources/views/Psu/modals/receive-kelurahan.blade.php --}}
<!-- Modal Receive Kelurahan -->
<div class="modal fade" id="receiveKelurahanModal" tabindex="-1" role="dialog" aria-labelledby="receiveKelurahanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="receiveKelurahanModalLabel">
                    <i class="fas fa-inbox"></i> Terima PSU di Kelurahan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="receiveKelurahanForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Proses Penerimaan di Kelurahan</strong><br>
                        Dengan menerima PSU ini, sistem akan otomatis membuat:
                        <ul class="mb-0 mt-2">
                            <li>Surat Tanda Terima untuk pemohon</li>
                            <li>Lembar Disposisi untuk Lurah</li>
                        </ul>
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
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="receiveKelurahanBtn">
                        <i class="fas fa-inbox"></i> Terima di Kelurahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showReceiveKelurahanModal(id, name) {
    // Reset form
    $('#receiveKelurahanForm')[0].reset();
    $('#confirmReceive').prop('checked', false);

    // Get PSU details via AJAX
    $.ajax({
        url: `/psu/${id}`,
        method: 'GET',
        success: function(response) {
            // Assuming we get PSU details, populate the modal
            // This would need to be implemented in the controller
            $('#receiveNomorSurat').val(response.nomor_surat || '');
            $('#receiveNamaPemohon').val(response.nama_lengkap || name);
            $('#receiveHal').val(response.hal || '');
        },
        error: function() {
            $('#receiveNomorSurat').val('');
            $('#receiveNamaPemohon').val(name);
            $('#receiveHal').val('');
        }
    });

    // Show modal
    $('#receiveKelurahanModal').modal('show');

    // Store PSU ID for form submission
    $('#receiveKelurahanForm').data('psu-id', id);
}

$(document).ready(function() {
    $('#receiveKelurahanForm').on('submit', function(e) {
        e.preventDefault();

        const psuId = $(this).data('psu-id');
        const btn = $('#receiveKelurahanBtn');
        const originalText = btn.html();

        // Validate confirmation checkbox
        if (!$('#confirmReceive').is(':checked')) {
            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi Diperlukan',
                text: 'Harap centang kotak konfirmasi sebelum melanjutkan.'
            });
            return;
        }

        btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

        $.ajax({
            url: `/psu/${psuId}/receive-kelurahan`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#receiveKelurahanModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <p>${response.message}</p>
                            <div class="mt-3">
                                <a href="${response.data.tanda_terima_url}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-receipt"></i> Lihat Tanda Terima
                                </a>
                                <a href="${response.data.disposisi_url}" target="_blank" class="btn btn-warning btn-sm">
                                    <i class="fas fa-clipboard-list"></i> Lihat Disposisi
                                </a>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Refresh table
                        if (window.psuKelurahanPage) {
                            window.psuKelurahanPage.refreshTable();
                        }
                    });
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses.';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg
                });
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>
