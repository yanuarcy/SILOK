{{-- resources/views/Psu/modals/process-lurah.blade.php --}}
<!-- Modal Process Lurah -->
<div class="modal fade" id="processLurahModal" tabindex="-1" role="dialog" aria-labelledby="processLurahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="processLurahModalLabel">
                    <i class="fas fa-user-tie"></i> Proses Disposisi Lurah
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="processLurahForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
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
                            <!-- Instruksi/Arahan -->
                            <div class="form-group">
                                <label><strong>Instruksi/Arahan</strong></label>
                                <div class="border rounded p-3">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input instruksi-checkbox" id="instruksi1" value="Setuju untuk diproses">
                                        <label class="custom-control-label" for="instruksi1">Setuju untuk diproses</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input instruksi-checkbox" id="instruksi2" value="Perlu kajian lebih lanjut">
                                        <label class="custom-control-label" for="instruksi2">Perlu kajian lebih lanjut</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input instruksi-checkbox" id="instruksi3" value="Koordinasi dengan bagian terkait">
                                        <label class="custom-control-label" for="instruksi3">Koordinasi dengan bagian terkait</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input instruksi-checkbox" id="instruksi4" value="Mohon ditindaklanjuti">
                                        <label class="custom-control-label" for="instruksi4">Mohon ditindaklanjuti</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input instruksi-checkbox" id="instruksi5" value="Lain-lain">
                                        <label class="custom-control-label" for="instruksi5">Lain-lain (sebutkan di catatan)</label>
                                    </div>
                                </div>
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
                        </div>

                        <div class="col-md-6">
                            <!-- Tanda Tangan Digital Lurah -->
                            <div class="form-group">
                                <label>Tanda Tangan Digital Lurah <span class="text-danger">*</span></label>
                                <div class="border rounded p-2">
                                    <canvas id="signaturePadLurah"
                                            class="signature-pad"
                                            width="400"
                                            height="200"
                                            style="border: 1px dashed #ccc; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-sm btn-secondary" id="clearSignatureLurah">
                                            <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        Gunakan mouse atau touch untuk membuat tanda tangan
                                    </small>
                                </div>
                                <input type="hidden" name="ttd_lurah_disposisi" id="ttdLurahInput">
                            </div>

                            <!-- Diteruskan Kepada -->
                            <div class="form-group">
                                <label><strong>Diteruskan Kepada</strong></label>
                                <div class="border rounded p-3">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="teruskan1" name="diteruskan_kepada" value="Back Office" checked>
                                        <label class="custom-control-label" for="teruskan1">Back Office untuk pembuatan E-Surat</label>
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
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning" id="processLurahBtn">
                        <i class="fas fa-signature"></i> Proses & Tanda Tangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let signaturePadLurah;

function showProcessLurahModal(id, name) {
    // Reset form
    $('#processLurahForm')[0].reset();
    $('#confirmProcessLurah').prop('checked', false);
    $('.instruksi-checkbox').prop('checked', false);

    // Get PSU details
    $.ajax({
        url: `/psu/${id}`,
        method: 'GET',
        success: function(response) {
            $('#processLurahNomorSurat').val(response.nomor_surat || '');
            $('#processLurahNamaPemohon').val(response.nama_lengkap || name);
            $('#processLurahHal').val(response.hal || '');
        },
        error: function() {
            $('#processLurahNomorSurat').val('');
            $('#processLurahNamaPemohon').val(name);
            $('#processLurahHal').val('');
        }
    });

    // Show modal
    $('#processLurahModal').modal('show');

    // Initialize signature pad after modal is shown
    setTimeout(() => {
        initializeLurahSignaturePad();
    }, 500);

    // Store PSU ID
    $('#processLurahForm').data('psu-id', id);
}

function initializeLurahSignaturePad() {
    const canvas = document.getElementById('signaturePadLurah');
    if (canvas) {
        signaturePadLurah = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255,255,255,0)',
            penColor: 'rgb(0, 0, 0)',
            velocityFilterWeight: 0.7,
            minWidth: 0.5,
            maxWidth: 2.5,
        });

        // Clear signature button
        $('#clearSignatureLurah').on('click', function() {
            signaturePadLurah.clear();
            $('#ttdLurahInput').val('');
        });

        // Auto-save signature when changed
        signaturePadLurah.onEnd = function() {
            $('#ttdLurahInput').val(signaturePadLurah.toDataURL());
        };
    }
}

$(document).ready(function() {
    // Handle instruksi checkbox changes
    $('.instruksi-checkbox').on('change', function() {
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

    $('#processLurahForm').on('submit', function(e) {
        e.preventDefault();

        const psuId = $(this).data('psu-id');
        const btn = $('#processLurahBtn');
        const originalText = btn.html();

        // Validate
        if (!$('#confirmProcessLurah').is(':checked')) {
            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi Diperlukan',
                text: 'Harap centang kotak konfirmasi sebelum melanjutkan.'
            });
            return;
        }

        if (!signaturePadLurah || signaturePadLurah.isEmpty()) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanda Tangan Diperlukan',
                text: 'Harap buat tanda tangan digital sebelum melanjutkan.'
            });
            return;
        }

        // Set signature data
        $('#ttdLurahInput').val(signaturePadLurah.toDataURL());

        btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

        $.ajax({
            url: `/psu/${psuId}/process-lurah`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#processLurahModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
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
