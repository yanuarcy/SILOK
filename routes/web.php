<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\DataSkmController;
use App\Http\Controllers\InformasiUmumController;
use App\Http\Controllers\KependudukanController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\BankDataController;
use App\Http\Controllers\AdminBankDataController;
use App\Http\Controllers\SpesimenController;
use App\Http\Controllers\PerpuController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\SubLayananController;
use App\Http\Controllers\LayananItemController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\RegistrationOptionController;
use App\Http\Controllers\ApplicantTypeController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\APIWhatsappDataController;
use App\Http\Controllers\AntarmukaController;
use App\Http\Controllers\PemohonController;
use App\Http\Controllers\QueueDisplayController;
use App\Http\Controllers\SuratMasukPsuController;
use App\Http\Controllers\PsuController;
use App\Http\Controllers\SkawController;
use App\Http\Controllers\PuntadewaController;
use App\Http\Controllers\SuratPengantarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Middleware\CekRole;


Route::fallback(function () {
    return view('app.404');
});

// Route::middleware(['session.expired'])->group(function () {

// });

Route::redirect('/', '/home');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', function () {
    return view('app.index');
});

Route::get('/PDFView', function () {
    return view('layanan.Perpu.PDFview');
});

Route::get('/About', [AboutController::class, 'index'])->name('About');
// Route::get('/Pegawai', [PegawaiController::class, 'index'])->name('Pegawai');
Route::get('/kepegawaian', [PegawaiController::class, 'publicIndex'])->name('kepegawaian');
Route::get('/Contact', [ContactController::class, 'index'])->name('Contact');
// Route::get('/Layanan-Adminduk', [LayananController::class, 'index'])->name('Adminduk');
// Route::get('/Layanan-Adminduk/{layananType}', [LayananController::class, 'show'])->name('Adminduk.show');
// Route::get('/Layanan-Adminduk/{layananType}/{subLayananType}', [LayananController::class, 'showSubLayanan'])->name('Adminduk.showSubLayanan');
// Route::get('/Layanan-Adminduk/{layananType}/{subLayananType}/{itemType?}', [LayananController::class, 'showRegistrationOptions'])->name('Adminduk.showRegistrationOptions');
// Route::get('/Layanan-Adminduk/{layananType}/{subLayananType}/{itemType}/{registrationType}', [LayananController::class, 'showApplicantTypes'])->name('Adminduk.showApplicantTypes');
// Route::get('/Layanan-Adminduk/{layananType}/{subLayananType}/{itemType}/{registrationType?}/{applicantType?}', [LayananController::class, 'showRegistrationForm'])->name('Adminduk.showRegistrationForm');

// Routes untuk user
Route::prefix('Layanan-Adminduk')->group(function () {
    // Index page
    Route::get('/', [LayananController::class, 'index'])->name('Adminduk');

    // Show specific layanan
    Route::get('/{layanan:slug}', [LayananController::class, 'show'])->name('Adminduk.show');

    // Show sub layanan
    Route::get('/{layanan:slug}/{subLayanan:slug}', [LayananController::class, 'showSubLayanan'])
        ->name('Adminduk.showSubLayanan');

    // Show registration options
    Route::get('/{layanan:slug}/{subLayanan:slug}/{itemType?}', [LayananController::class, 'showRegistrationOptions'])
        ->name('Adminduk.showRegistrationOptions');

    // Show applicant types
    Route::get('/{layanan:slug}/{subLayanan:slug}/{itemType}/{registrationType}', [LayananController::class, 'showApplicantTypes'])
        ->name('Adminduk.showApplicantTypes');

    // Show registration form
    Route::get('/{layanan:slug}/{subLayanan:slug}/{itemType}/{registrationType?}/{applicantType?}', [LayananController::class, 'showRegistrationForm'])
        ->name('Adminduk.showRegistrationForm');
});

Route::prefix('adminduk')->group(function () {

    // Route untuk submit form pendaftaran
    Route::post('/registration/submit', [AntrianController::class, 'submitRegistration'])
         ->name('adminduk.registration.submit');

    // Route untuk halaman sukses
    Route::get('/registration/success', [AntrianController::class, 'registrationSuccess'])
         ->name('adminduk.registration.success');

    // Route untuk validasi pemohon lama (AJAX)
    Route::post('/validate-pemohon', [AntrianController::class, 'validatePemohon'])
         ->name('adminduk.validatePemohon');

    // Route untuk cek status antrian
    Route::get('/status/{noAntrian}', [AntrianController::class, 'checkAntrianStatus'])
         ->name('adminduk.checkStatus');

    // Route untuk mendapatkan statistik antrian
    Route::get('/stats', [AntrianController::class, 'getAntrianStats'])
         ->name('adminduk.stats');
});

// Route::get('/Master-Data/Layanan', function () {
//     return view('admin.masterdata.Layanan.index');
// });

Auth::routes();
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/otp/{uuid}', [LoginController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/{uuid}', [LoginController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend/{uuid}', [LoginController::class, 'resendOtp'])->name('otp.resend');

Route::get('/register/otp/{email}', [RegisterController::class, 'showOtpForm'])->name('register.otp');
Route::post('/register/verify-otp/{email}', [RegisterController::class, 'verifyOtp'])->name('register.verify-otp');
Route::post('/register/resend-otp/{email}', [RegisterController::class, 'resendOtp'])->name('register.resend-otp');



// Route::resource('/Layanan', LayananController::class);

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/resetForm', [ResetPasswordController::class, 'showResetForm'])->name('password.resetForm');

// Dashboard
Route::get('/Dashboard/General', function () {
    return view('admin.dashboard.index', ['type_menu' => 'dashboard']);
})->name('Dashboard.General')->middleware(['auth']);
// Route::get('/Dashboard/General', function () {
//     return view('dashboard.index', ['type_menu' => 'dashboard']);
// })->name('Dashboard.General')->middleware(['auth', 'cekrole:admin']);
Route::get('/Dashboard/Front-Office', [AntrianController::class, 'index'])->name('Dashboard.FrontOffice')->middleware(['auth', 'cekrole:admin,Operator,Front Office']);


// Routes untuk admin CRUD
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('layanan/data', [LayananController::class, 'getData'])->name('layanan.data');
    Route::get('sub-layanan/data', [SubLayananController::class, 'getData'])->name('sub-layanan.data');
    Route::get('layanan-item-data', [LayananItemController::class, 'getData'])->name('layanan-item.data');
    Route::get('registration-options/data', [RegistrationOptionController::class, 'data'])->name('registration-options.data');
    Route::get('applicant-types/data', [ApplicantTypeController::class, 'data'])->name('applicant-types.data');

    Route::get('/layanan/create', [LayananController::class, 'create'])->name('layanan.create');
    Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
    Route::get('/layanan/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
    Route::put('/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');

    // AJAX Routes untuk Kode Layanan
    Route::post('/layanan/check-kode', [LayananController::class, 'checkKode'])->name('layanan.checkKode');
    Route::get('/layanan/kode-mapping', [LayananController::class, 'getKodeMapping'])->name('layanan.kodeMapping');
    Route::get('/layanan/available-kodes', [LayananController::class, 'getAvailableKodes'])->name('layanan.availableKodes');

    Route::resource('sub-layanan', SubLayananController::class);

    Route::get('/layanan-item/{item}/edit', [LayananItemController::class, 'edit'])->name('layanan-item.edit');
    Route::put('/layanan-item/{item}', [LayananItemController::class, 'update'])->name('layanan-item.update');
    Route::delete('/layanan-item/{item}', [LayananItemController::class, 'destroy'])->name('layanan-item.destroy');

    Route::resource('registration-options', RegistrationOptionController::class);

    Route::resource('applicant-types', ApplicantTypeController::class);
});


Route::middleware(['auth'])->group(function () {

    // Route yang bisa diakses oleh admin, Operator, Front Office, Back Office, dan Lurah
    Route::middleware(['cekrole:admin,Operator,Front Office,Back Office,Lurah'])->group(function () {

        // Route yang bisa diakses oleh semua role
        Route::get('members-data', [MemberController::class, 'getData'])->name('members.data');
        // Master Data Member
        Route::resource('/Master-Data/Member', MemberController::class);

        // Data Layanan routes
        Route::prefix('Master-Data/Data-Layanan')->group(function () {
            Route::get('/Layanan', function () {
                return view('admin.masterdata.Layanan.index', ['type_menu' => 'master-data']);
            })->name('masterdata.layanan');

            Route::get('/sub-layanan', function () {
                return view('admin.masterdata.Layanan.sub-layanan-index', ['type_menu' => 'master-data']);
            })->name('masterdata.sub-layanan');

            Route::get('/layanan-item', function () {
                return view('admin.masterdata.Layanan.layanan-item-index', ['type_menu' => 'master-data']);
            })->name('masterdata.layanan-item');

            Route::get('/kategori-pendaftaran', function () {
                return view('admin.masterdata.Layanan.kategori-pendaftaran', ['type_menu' => 'master-data']);
            })->name('masterdata.kategori-pendaftaran');

            Route::get('/kategori-pemohon', function () {
                return view('admin.masterdata.Layanan.kategori-pemohon', ['type_menu' => 'master-data']);
            })->name('masterdata.kategori-pemohon');
        });

        // Master Data Lainnya
        // Route::get('/Master-Data/Loket', function () {
        //     return view('admin.masterdata.Loket.data-loket', ['type_menu' => 'master-data']);
        // })->name('masterdata.loket');
        Route::resource('/Master-Data/Loket', LoketController::class);
        // Route::get('/Master-Data/Loket/create', [LoketController::class, 'create'])->name('Loket.create');
        Route::get('lokets-data', [LoketController::class, 'getData'])->name('lokets.data');

        // Route::get('/Master-Data/ApiWhatsapp', function () {
        //     return view('admin.masterdata.apiWhatsapp.data-apiWhatsapp', ['type_menu' => 'master-data']);
        // })->name('masterdata.apiWhatsapp');

        Route::resource('/Master-Data/ApiWhatsapp', APIWhatsappDataController::class);
        Route::get('APIWhatsapp-data', [APIWhatsappDataController::class, 'getData'])->name('ApiWhatsapp.getData');
        // Route::post('APIWhatsapp-data/{id}/toggle-active', [APIWhatsappDataController::class, 'toggleActive'])->name('whatsapp-owners.toggle-active');

        Route::prefix('api-whatsapp')->group(function () {
            // Toggle active dengan quota check
            Route::post('/{id}/toggle-active', [APIWhatsappDataController::class, 'toggleActive'])->name('ApiWhatsapp.toggleActive');

            // Top up quota
            Route::post('/{id}/top-up', [APIWhatsappDataController::class, 'topUpQuota'])->name('ApiWhatsapp.topUp');

            // Get summary data untuk dashboard
            Route::get('/summary', [APIWhatsappDataController::class, 'getSummary'])->name('ApiWhatsapp.summary');

            // Auto switch ke quota terbanyak
            Route::post('/auto-switch-max', [APIWhatsappDataController::class, 'autoSwitchToMaxQuota'])->name('ApiWhatsapp.autoSwitchMax');
        });

        // Route::get('/Master-Data/Antarmuka', function () {
        //     return view('admin.masterdata.data-antarmuka', ['type_menu' => 'master-data']);
        // })->name('masterdata.antarmuka');

        Route::resource('Master-Data/Antarmuka', AntarmukaController::class);
        Route::get('antarmuka-data', [AntarmukaController::class, 'getData'])->name('antarmuka.data');
        Route::post('antarmuka/{antarmuka}/activate', [AntarmukaController::class, 'activate'])->name('antarmuka.activate');
        Route::post('antarmuka/{antarmuka}/deactivate', [AntarmukaController::class, 'deactivate'])->name('antarmuka.deactivate');
        Route::post('antarmuka/{id}/volume', [AntarmukaController::class, 'updateVolume'])->name('antarmuka.updateVolume');

         Route::get('/Master-Data/Pemohon', [PemohonController::class, 'index'])->name('masterdata.pemohon');

        // Route untuk mendapatkan data via DataTables
        Route::get('/Master-Data/Pemohon/data', [PemohonController::class, 'getData'])->name('pemohon.getData');

        // Route untuk statistik
        Route::get('/Master-Data/Pemohon/statistics', [PemohonController::class, 'getStatistics'])->name('pemohon.statistics');

        // Route untuk filter options
        Route::get('/Master-Data/Pemohon/filter-options', [PemohonController::class, 'getFilterOptions'])->name('pemohon.filterOptions');

        // Route untuk export
        Route::get('/Master-Data/Pemohon/export/excel', [PemohonController::class, 'exportExcel'])->name('pemohon.exportExcel');
        Route::get('/Master-Data/Pemohon/export/pdf', [PemohonController::class, 'exportPdf'])->name('pemohon.exportPdf');
        Route::get('/pemohon/preview-pdf', [PemohonController::class, 'previewPdf'])->name('pemohon.previewPdf');
        Route::get('/pemohon/print-data', [PemohonController::class, 'printData'])->name('pemohon.printData');

        Route::prefix('Master-Data/Pegawai')->name('Pegawai.')->group(function () {
            Route::get('/', [PegawaiController::class, 'index'])->name('index');
            Route::get('/data', [PegawaiController::class, 'data'])->name('data');
            Route::get('/create', [PegawaiController::class, 'create'])->name('create');
            Route::post('/', [PegawaiController::class, 'store'])->name('store');
            Route::get('/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('edit');
            Route::put('/{pegawai}', [PegawaiController::class, 'update'])->name('update');
            Route::delete('/{pegawai}', [PegawaiController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['cekrole:admin,Operator,Front Office,Back Office,Lurah'])->prefix('Master-Data/')->name('admin.')->group(function () {
        Route::resource('Data-SKM', DataSkmController::class)->except(['create', 'edit', 'update']);

        // Route specific harus diletakkan SEBELUM route dengan parameter
        Route::get('data-skm/data', [DataSkmController::class, 'data'])->name('data-skm.data');
        Route::get('data-skm/summary', [DataSkmController::class, 'getSummary'])->name('data-skm.summary');
        Route::post('data-skm/bulk-toggle-testimonial', [DataSkmController::class, 'bulkToggleTestimonial'])->name('data-skm.bulk-toggle-testimonial');

        // Route dengan parameter harus di paling bawah
        Route::get('data-skm/{id}', [DataSkmController::class, 'show'])->name('data-skm.show');
        Route::patch('data-skm/{dataSkm}/toggle-status', [DataSkmController::class, 'toggleStatus'])->name('data-skm.toggle-status');
    });

    // Admin Routes untuk Data Kependudukan
    Route::prefix('Master-Data')->name('admin.')->group(function () {
        // Route group untuk kependudukan
        Route::prefix('Data-Kependudukan')->name('kependudukan.')->group(function () {
            Route::get('/', [KependudukanController::class, 'adminIndex'])->name('index');
            Route::get('/edit', [KependudukanController::class, 'edit'])->name('edit');
            Route::put('/update', [KependudukanController::class, 'update'])->name('update');
            Route::get('/export', [KependudukanController::class, 'exportData'])->name('export');
            Route::post('/import', [KependudukanController::class, 'importData'])->name('import');
        });
    });

    Route::prefix('Master-Data/')->name('admin.')->group(function () {
        // CRUD routes
        Route::resource('Data-Perpu', PerpuController::class)->names([
            'index' => 'Perpu.index',
            'create' => 'Perpu.create',
            'store' => 'Perpu.store',
            'show' => 'Perpu.show',
            'edit' => 'Perpu.edit',
            'update' => 'Perpu.update',
            'destroy' => 'Perpu.destroy'
        ]);

        // Additional admin routes
        Route::get('perpu-data', [PerpuController::class, 'data'])->name('Perpu.data');
        Route::get('perpu/{perpu}/download', [PerpuController::class, 'download'])->name('Perpu.download');
        Route::post('perpu/{perpu}/track-view', [PerpuController::class, 'trackView'])->name('Perpu.track-view');
    });

    Route::middleware(['cekrole:admin,Operator,Ketua RT,Ketua RW'])->group(function () {
        Route::prefix('Master-Data')->name('admin.masterdata.')->group(function () {

            // Bank Data CRUD Routes
            Route::resource('BankData', AdminBankDataController::class);

            // DataTables Route
            Route::get('BankData/data/table', [AdminBankDataController::class, 'data'])->name('BankData.data');

            // Additional routes if needed
            Route::post('BankData/{bankData}/toggle-status', [AdminBankDataController::class, 'toggleStatus'])->name('BankData.toggle-status');
            Route::get('BankData/{bankData}/download', [AdminBankDataController::class, 'downloadFile'])->name('BankData.download-file');
            Route::delete('BankData/{bankData}/remove-file', [AdminBankDataController::class, 'removeFile'])->name('BankData.remove-file');
        });
    });

    Route::middleware(['cekrole:admin,Ketua RT,Ketua RW,Front Office,Back Office,Lurah'])->group(function () {
        Route::prefix('Master-Data')->name('admin.masterdata.')->group(function () {

            // Spesimen TTD & Stempel CRUD Routes
            Route::resource('Spesimen', SpesimenController::class);

            // DataTables Route
            Route::get('Spesimen/data/table', [SpesimenController::class, 'data'])->name('Spesimen.data');
            // Route::get('Spesimen/create', [SpesimenController::class, 'create']);
            // Route::get('Spesimen/{spesimen}/edit', [SpesimenController::class, 'edit']);


            // Additional routes
            Route::get('Spesimen/{spesimen}/download-ttd', [SpesimenController::class, 'downloadTtd'])->name('Spesimen.download-ttd');
            Route::get('Spesimen/{spesimen}/download-stempel', [SpesimenController::class, 'downloadStempel'])->name('Spesimen.download-stempel');
            Route::post('Spesimen/{spesimen}/toggle-status', [SpesimenController::class, 'toggleStatus'])->name('Spesimen.toggle-status');

            // API route untuk mendapatkan user berdasarkan jabatan
            Route::get('Spesimen/api/users-by-jabatan', [SpesimenController::class, 'getUsersByJabatan'])->name('Spesimen.users-by-jabatan');
        });
    });

    // PSU CRUD Routes
    Route::resource('psu', PsuController::class);

    Route::get('psu-permohonan-saya', [PsuController::class, 'PermohonanSaya'])->name('psu.permohonan-saya');
    Route::get('psu-semua-permohonan', [PsuController::class, 'SemuaPermohonan'])->name('psu.semua-permohonan');

    // PSU DataTables and Summary
    // Route::get('psu-data', [PsuController::class, 'getData'])->name('psu.getData');
    Route::get('/getData', [PsuController::class, 'getData'])->name('psu.getData');
    Route::get('psu-summary', [PsuController::class, 'getSummary'])->name('psu.getSummary');

    // AJAX endpoints untuk PSU
    Route::post('psu/get-warga-by-rt', [PsuController::class, 'getWargaByRT'])->name('psu.getWargaByRT');
    Route::post('psu/get-warga-by-rw', [PsuController::class, 'getWargaByRW'])->name('psu.getWargaByRW');
    Route::post('psu/get-rt-in-rw', [PsuController::class, 'getRTInRW'])->name('psu.getRTInRW');
    Route::post('psu/get-ketua-rt', [PsuController::class, 'getKetuaRT'])->name('psu.getKetuaRT');
    Route::post('psu/get-ketua-rw', [PsuController::class, 'getKetuaRW'])->name('psu.getKetuaRW');

    // PSU PDF Routes
    Route::get('psu/{psu}/preview-pdf', [PsuController::class, 'previewPDF'])->name('psu.preview-pdf');
    Route::get('psu/{psu}/download-pdf', [PsuController::class, 'downloadPDF'])->name('psu.download-pdf');

    // Download routes for new documents
    Route::get('psu/{psu}/download-tanda-terima', [PsuController::class, 'downloadTandaTerima'])->name('psu.download-tanda-terima');
    Route::get('psu/{psu}/download-disposisi', [PsuController::class, 'downloadDisposisi'])->name('psu.download-disposisi');
    Route::get('psu/{psu}/download-esurat', [PsuController::class, 'downloadEsurat'])->name('psu.download-esurat');
    // Route::get('tanda-terima', function() {
    //     return view('Psu.TandaTerima');
    // });

    Route::get('/{psu}/preview-tanda-terima', [PsuController::class, 'previewTandaTerima'])->name('psu.preview-tanda-terima');
    Route::get('psu/{psu}/preview-disposisi', [PsuController::class, 'previewDisposisi'])->name('psu.preview-disposisi');

    // PSU Approval Routes - RT
    Route::get('psu/{psu}/get-rt-spesimen', [PsuController::class, 'getRTSpesimen'])->name('psu.get-rt-spesimen');
    Route::post('psu/{psu}/approve-rt', [PsuController::class, 'approveRT'])->name('psu.approve-rt');
    Route::post('psu/{psu}/reject-rt', [PsuController::class, 'rejectRT'])->name('psu.reject-rt');

    // PSU Approval Routes - RW
    Route::get('psu/{psu}/get-rw-spesimen', [PsuController::class, 'getRWSpesimen'])->name('psu.get-rw-spesimen');
    Route::post('psu/{psu}/approve-rw', [PsuController::class, 'approveRW'])->name('psu.approve-rw');
    Route::post('psu/{psu}/reject-rw', [PsuController::class, 'rejectRW'])->name('psu.reject-rw');

    // PSU Approval Routes - Kelurahan
    Route::get('/psu/{psu}/get-front-office-spesimen', [PsuController::class, 'getFrontOfficeSpesimen'])->name('psu.getFrontOfficeSpesimen');
    Route::get('psu/{psu}/get-kelurahan-spesimen', [PsuController::class, 'getKelurahanSpesimen'])->name('psu.get-kelurahan-spesimen');
    Route::post('psu/{psu}/approve-kelurahan', [PsuController::class, 'approveKelurahan'])->name('psu.approve-kelurahan');
    Route::post('psu/{psu}/reject-kelurahan', [PsuController::class, 'rejectKelurahan'])->name('psu.reject-kelurahan');
    Route::get('psu/{psu}/get-lurah-spesimen', [PsuController::class, 'getLurahSpesimen'])->name('psu.get-lurah-spesimen');

    // NEW: Kelurahan Workflow Routes
    Route::post('psu/{psu}/receive-kelurahan', [PsuController::class, 'receiveKelurahan'])->name('psu.receive-kelurahan');
    Route::post('psu/{psu}/process-lurah', [PsuController::class, 'processLurah'])->name('psu.process-lurah');
    Route::post('psu/{psu}/process-back-office', [PsuController::class, 'processBackOffice'])->name('psu.process-back-office');

    // PSU Admin Routes
    Route::post('psu/sync-all-to-user-application', [PsuController::class, 'syncAllToUserApplication'])->name('psu.sync-all');
    Route::get('psu/check-data-integrity', [PsuController::class, 'checkDataIntegrity'])->name('psu.check-integrity');

    Route::prefix('surat-masuk/psu')->name('surat-masuk.psu.')->group(function () {
        Route::get('/', [SuratMasukPsuController::class, 'index'])->name('index');
        Route::get('/data', [SuratMasukPsuController::class, 'getData'])->name('data');
        Route::get('/summary', [SuratMasukPsuController::class, 'getSummary'])->name('summary');
        Route::get('/unread-count', [SuratMasukPsuController::class, 'getUnreadCount'])->name('unread-count');

        // UPDATED: Gunakan PSU ID langsung, bukan UserApplication ID
        Route::get('/{psuId}/preview', [SuratMasukPsuController::class, 'preview'])->name('preview');
        Route::get('/{psuId}/download', [SuratMasukPsuController::class, 'download'])->name('download');
    });

    // Main SKAW Routes
    Route::prefix('skaw')->name('skaw.')->group(function () {

        // Index route (redirect based on role)
        Route::get('/', [SkawController::class, 'index'])->name('index');

        // View 1: Permohonan Saya (User & Admin)
        Route::get('/permohonan-saya', [SkawController::class, 'permohonanSaya'])->name('permohonan-saya');

        // View 2: Semua Permohonan (Front Office, Back Office, Lurah, Camat)
        Route::get('/semua-permohonan', [SkawController::class, 'semuaPermohonan'])->name('semua-permohonan');

        // View 3: Daftar Sidang SKAW
        Route::get('/daftar-sidang', [SkawController::class, 'daftarSidang'])->name('daftar-sidang');

        // View 4: Telah Sidang (Menunggu Approval)
        Route::get('/telah-sidang', [SkawController::class, 'telahSidang'])->name('telah-sidang');

        // View 5: SKAW Jadi (Final Documents)
        Route::get('/skaw-jadi', [SkawController::class, 'skawJadi'])->name('skaw-jadi');

        // CRUD Operations
        Route::get('/create', [SkawController::class, 'create'])->name('create');
        Route::post('/store', [SkawController::class, 'store'])->name('store');
        Route::get('/{skaw}', [SkawController::class, 'show'])->name('show');
        Route::get('/{skaw}/edit', [SkawController::class, 'edit'])->name('edit');
        Route::put('/{skaw}', [SkawController::class, 'update'])->name('update');
        Route::delete('/{skaw}', [SkawController::class, 'destroy'])->name('destroy');

        // DataTables and AJAX Routes
        Route::get('/data/get-data', [SkawController::class, 'getData'])->name('getData');
        Route::get('/data/summary', [SkawController::class, 'getSummary'])->name('getSummary');

        // Document Preview Routes
        Route::get('/{skaw}/preview-tanda-terima', [SkawController::class, 'previewTandaTerima'])->name('preview-tanda-terima');
        Route::get('/{skaw}/preview-draft', [SkawController::class, 'previewDraft'])->name('preview-draft');
        Route::get('/{skaw}/preview-final', [SkawController::class, 'previewFinal'])->name('preview-final');

        // Workflow Approval Routes
        Route::post('/{skaw}/front-office-approve', [SkawController::class, 'frontOfficeApprove'])->name('front-office-approve');
        Route::post('/{skaw}/back-office-approve', [SkawController::class, 'backOfficeApprove'])->name('back-office-approve');
        Route::post('/{skaw}/lurah-approve', [SkawController::class, 'lurahApprove'])->name('lurah-approve');
        Route::post('/{skaw}/camat-approve', [SkawController::class, 'camatApprove'])->name('camat-approve');

        // Jadwal Sidang Routes
        Route::post('/create-jadwal-sidang', [SkawController::class, 'createJadwalSidang'])->name('create-jadwal-sidang');
        Route::put('/update-jadwal-sidang/{jadwal}', [SkawController::class, 'updateJadwalSidang'])->name('update-jadwal-sidang');
        Route::post('/selesai-sidang', [SkawController::class, 'selesaiSidang'])->name('selesai-sidang');

        // Evidence Upload Routes
        Route::post('/upload-evidence', [SkawController::class, 'uploadEvidence'])->name('upload-evidence');
        Route::delete('/delete-evidence/{evidence}', [SkawController::class, 'deleteEvidence'])->name('delete-evidence');

        // Final SKAW Upload Routes
        Route::post('/upload-final', [SkawController::class, 'uploadFinal'])->name('upload-final');
        Route::put('/{skaw}/edit-final', [SkawController::class, 'editFinal'])->name('edit-final');
        Route::post('/{skaw}/mark-picked-up', [SkawController::class, 'markPickedUp'])->name('mark-picked-up');

        // Summary Statistics Routes
        Route::get('/summary/counts', [SkawController::class, 'getSummaryCounts'])->name('summary-counts');
        Route::get('/summary/telah-sidang', [SkawController::class, 'getTelahSidangSummary'])->name('telah-sidang-summary');
        Route::get('/summary/statistics', [SkawController::class, 'getStatistics'])->name('statistics');

        // File Management Routes
        Route::post('/upload-file', [SkawController::class, 'uploadFile'])->name('upload-file');
        Route::delete('/delete-file/{file}', [SkawController::class, 'deleteFile'])->name('delete-file');
        Route::get('/download-file/{file}', [SkawController::class, 'downloadFile'])->name('download-file');

        // Approval with Notes Routes
        Route::post('/approve-lurah', [SkawController::class, 'approveLurah'])->name('approve-lurah');
        Route::post('/approve-camat', [SkawController::class, 'approveCamat'])->name('approve-camat');
        Route::post('/reject-approval', [SkawController::class, 'rejectApproval'])->name('reject-approval');

        // Additional Helper Routes
        Route::get('/check-nomor-surat/{nomor}', [SkawController::class, 'checkNomorSurat'])->name('check-nomor-surat');
        Route::get('/generate-nomor-surat', [SkawController::class, 'generateNomorSurat'])->name('generate-nomor-surat');

        // Export Routes
        Route::get('/export/excel', [SkawController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf', [SkawController::class, 'exportPdf'])->name('export-pdf');

        // Notification Routes
        Route::get('/notifications', [SkawController::class, 'getNotifications'])->name('notifications');
        Route::post('/mark-notification-read', [SkawController::class, 'markNotificationRead'])->name('mark-notification-read');
    });

    // Puntadewa Routes
    Route::resource('puntadewa', PuntadewaController::class);

    // Data endpoint for DataTables
    Route::get('puntadewa-data', [PuntadewaController::class, 'getData'])
         ->name('puntadewa.getData');

    // Summary endpoint for statistics
    Route::get('puntadewa-summary', [PuntadewaController::class, 'getSummary'])
         ->name('puntadewa.getSummary');

    // RT Approval routes
    Route::post('puntadewa/{puntadewa}/approve-rt', [PuntadewaController::class, 'approveRT'])
         ->name('puntadewa.approve-rt')
         ->middleware('cekrole:Ketua RT');

    Route::post('puntadewa/{puntadewa}/reject-rt', [PuntadewaController::class, 'rejectRT'])
         ->name('puntadewa.reject-rt')
         ->middleware('cekrole:Ketua RT');

    // RW Approval routes
    Route::post('puntadewa/{puntadewa}/approve-rw', [PuntadewaController::class, 'approveRW'])
         ->name('puntadewa.approve-rw')
         ->middleware('cekrole:Ketua RW');

    Route::post('puntadewa/{puntadewa}/reject-rw', [PuntadewaController::class, 'rejectRW'])
         ->name('puntadewa.reject-rw')
         ->middleware('cekrole:Ketua RW');

    // PDF routes
    Route::get('puntadewa/{puntadewa}/preview-pdf', [PuntadewaController::class, 'previewPDF'])
         ->name('puntadewa.preview-pdf');

    Route::get('puntadewa/{puntadewa}/download-pdf', [PuntadewaController::class, 'downloadPDF'])
         ->name('puntadewa.download-pdf');

    Route::get('/puntadewa/{puntadewa}', [PuntadewaController::class, 'show'])->name('puntadewa.show');

    // Di dalam group routes Puntadewa
    Route::get('puntadewa/{puntadewa}/get-rt-spesimen', [PuntadewaController::class, 'getRTSpesimen'])->name('puntadewa.get-rt-spesimen');
    Route::get('puntadewa/{puntadewa}/get-rw-spesimen', [PuntadewaController::class, 'getRWSpesimen'])->name('puntadewa.get-rw-spesimen');

    // Main CRUD routes
    Route::resource('surat-pengantar', SuratPengantarController::class);

    // Route untuk validasi RW-RT
    Route::post('/surat-pengantar/validate-rw-rt', [SuratPengantarController::class, 'validateRwRt'])
        ->name('surat-pengantar.validate-rw-rt');

    // DataTables and Summary
    Route::get('surat-pengantar-data', [SuratPengantarController::class, 'getData'])->name('surat-pengantar.getData');
    Route::get('/surat-pengantar/data', [SuratPengantarController::class, 'getData'])->name('surat-pengantar.data');
    Route::get('surat-pengantar-summary', [SuratPengantarController::class, 'getSummary'])->name('surat-pengantar.getSummary');

    // PDF functions
    Route::get('surat-pengantar/{suratPengantar}/preview-pdf', [SuratPengantarController::class, 'previewPDF'])->name('surat-pengantar.preview-pdf');
    Route::get('surat-pengantar/{suratPengantar}/download-pdf', [SuratPengantarController::class, 'downloadPDF'])->name('surat-pengantar.download-pdf');

    // RT Approval routes
    Route::get('surat-pengantar/{suratPengantar}/get-rt-spesimen', [SuratPengantarController::class, 'getRTSpesimen'])->name('surat-pengantar.get-rt-spesimen');
    Route::post('surat-pengantar/{suratPengantar}/approve-rt', [SuratPengantarController::class, 'approveRT'])->name('surat-pengantar.approve-rt');
    Route::post('surat-pengantar/{suratPengantar}/reject-rt', [SuratPengantarController::class, 'rejectRT'])->name('surat-pengantar.reject-rt');

    // RW Approval routes
    Route::get('surat-pengantar/{suratPengantar}/get-rw-spesimen', [SuratPengantarController::class, 'getRWSpesimen'])->name('surat-pengantar.get-rw-spesimen');
    Route::post('surat-pengantar/{suratPengantar}/approve-rw', [SuratPengantarController::class, 'approveRW'])->name('surat-pengantar.approve-rw');
    Route::post('surat-pengantar/{suratPengantar}/reject-rw', [SuratPengantarController::class, 'rejectRW'])->name('surat-pengantar.reject-rw');

});

Route::get('/skm', [DataSkmController::class, 'create'])->name('skm.create');
Route::post('/skm', [DataSkmController::class, 'store'])->name('skm.store');
Route::get('/skm/success', [DataSkmController::class, 'success'])->name('skm.success');
// Route::get('/api/testimonials', [DataSkmController::class, 'getTestimonials'])->name('api.testimonials');
Route::get('/testimonial', [DataSkmController::class, 'testimonials']);
Route::get('/api/testimonials', [DataSkmController::class, 'getTestimonialsApi']);

// Route untuk Informasi Umum
// Route::get('/informasi-umum', [InformasiUmumController::class, 'index'])->name('informasi-umum.index');


// Public Routes untuk Data Kependudukan
Route::prefix('informasi-umum')->name('informasi-umum.')->group(function () {
    Route::get('/', [InformasiUmumController::class, 'index'])->name('index');
    Route::get('/data-kependudukan', [KependudukanController::class, 'index'])->name('data-kependudukan');
    // Route::get('/persyaratan', [InformasiUmumController::class, 'persyaratan'])->name('persyaratan');
});

// Meeting Routes
Route::prefix('meeting')->name('meeting.')->middleware('auth')->group(function () {
    Route::get('/detail', [MeetingController::class, 'detail'])->name('detail');
    Route::delete('/{meeting}', [MeetingController::class, 'destroy'])->name('destroy');

    Route::middleware(['cekrole:admin,Operator,Ketua RT,Ketua RW'])->group(function () {
        Route::get('/create', [MeetingController::class, 'create'])->name('create');
        Route::post('/store', [MeetingController::class, 'store'])->name('store');
    });
});

// Routes untuk Panduan Layanan (Simple Version)
Route::prefix('panduan')->name('panduan.')->group(function () {
    Route::get('/', [PanduanController::class, 'index'])->name('index');
    Route::get('/detail/{slug}', [PanduanController::class, 'detail'])->name('detail');
    Route::get('/{slug}/download-formulir', [PanduanController::class, 'downloadFormulir'])->name('download-formulir');
});

// API Routes untuk Panduan (Simple)
Route::prefix('api/panduan')->name('api.panduan.')->group(function () {
    Route::get('/search', [PanduanController::class, 'search'])->name('search');
    Route::get('/layanan', [PanduanController::class, 'getLayananList'])->name('layanan');
});

// Route untuk download formulir dari sistem lama (backward compatibility)
Route::get('/download-formulir/{type}', function($type) {
    $slugMapping = [
        'ktp' => 'ktp-kartu-tanda-penduduk',
        'kk' => 'kk-kartu-keluarga',
        'kia' => 'kia-kartu-identitas-anak',
        'skaw' => 'skaw-surat-keterangan-ahli-waris',
        'skt' => 'skt-surat-keterangan-tanah',
        'akta-kelahiran' => 'akta-kelahiran'
    ];

    $slug = $slugMapping[$type] ?? $type;
    return redirect()->route('panduan.download-formulir', $slug);
})->name('download-formulir-legacy');

// API Routes untuk Data Kependudukan
Route::prefix('api/kependudukan')->name('api.kependudukan.')->group(function () {
    Route::get('/statistics', [KependudukanController::class, 'getStatistics'])->name('statistics');
    Route::get('/summary', [KependudukanController::class, 'getSummary'])->name('summary');
});

// Meeting API Routes
Route::prefix('api/meeting')->name('api.meeting.')->group(function () {
    Route::post('/update-status', [MeetingController::class, 'updateStatus'])->name('update-status');
    Route::get('/active', [MeetingController::class, 'getActiveMeetingsApi'])->name('active');
    Route::get('/completed', [MeetingController::class, 'getCompletedMeetingsApi'])->name('completed');
});

// API Routes for Informasi Umum
Route::prefix('api/informasi-umum')->name('api.informasi-umum.')->group(function () {
    Route::get('/statistics', [InformasiUmumController::class, 'getStatistics'])->name('statistics');
    Route::get('/meeting-schedule', [InformasiUmumController::class, 'getMeetingSchedule'])->name('meeting-schedule');
    Route::post('/refresh-statistics', [InformasiUmumController::class, 'refreshStatistics'])->name('refresh-statistics');
});

Route::get('/bank-data', [BankDataController::class, 'index'])->name('bankdata.index');
Route::get('/bank-data/{id}', [BankDataController::class, 'show'])->name('bankdata.show');
Route::get('/bank-data/jenis/{jenis}', [BankDataController::class, 'getByJenis'])->name('bankdata.by-jenis');
Route::get('/bank-data/rw/{nomor_rw}', [BankDataController::class, 'getByRW'])->name('bankdata.by-rw');
Route::get('/bank-data/rt/{nomor_rt}/rw/{nomor_rw}', [BankDataController::class, 'getByRT'])->name('bankdata.by-rt');

Route::prefix('api/bank-data')->group(function () {
    // Search endpoint
    Route::get('/search', [BankDataController::class, 'search'])->name('api.bank-data.search');

    // Suggestions endpoint untuk autocomplete
    Route::get('/suggestions', [BankDataController::class, 'suggestions'])->name('api.bank-data.suggestions');

    // Get statistics
    Route::get('/statistics', function() {
        $stats = [
            'total_kelurahan' => \App\Models\BankData::published()->active()->where('jenis_bank_data', 'Kelurahan')->count(),
            'total_rw' => \App\Models\BankData::published()->active()->where('jenis_bank_data', 'RW')->count(),
            'total_rt' => \App\Models\BankData::published()->active()->where('jenis_bank_data', 'RT')->count(),
            'total_files' => \App\Models\BankData::published()->active()->get()->sum(function($item) {
                $fotoCount = $item->files_foto ? count($item->files_foto) : 0;
                $videoCount = $item->files_video ? count($item->files_video) : 0;
                return $fotoCount + $videoCount;
            }),
            'total_views' => \App\Models\BankData::published()->active()->sum('view_count'),
        ];
        return response()->json($stats);
    })->name('api.bank-data.statistics');

    // Get by jenis
    Route::get('/jenis/{jenis}', function($jenis) {
        $bankData = \App\Models\BankData::published()
            ->active()
            ->where('jenis_bank_data', $jenis)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->paginate(12);

        return response()->json($bankData);
    })->name('api.bank-data.by-jenis');
});



// Public Routes - Untuk pengunjung website
Route::prefix('peraturan')->group(function () {
    Route::get('/', [PerpuController::class, 'publicIndex'])->name('perpu.index');
    Route::get('/{perpu}', [PerpuController::class, 'publicShow'])->name('perpu.show');
    Route::get('/{perpu}/download', [PerpuController::class, 'download'])->name('perpu.download');
    Route::get('/perpu/{id}/view', [PerpuController::class, 'viewPdf'])->name('perpu.view');
});

// API Routes (jika diperlukan untuk AJAX atau mobile app)
Route::prefix('api')->group(function () {
    Route::get('perpu', [PerpuController::class, 'apiIndex']);
    Route::get('perpu/{perpu}', [PerpuController::class, 'apiShow']);
    Route::get('perpu/search/{query}', [PerpuController::class, 'apiSearch']);
    Route::get('perpu/filter/{jenis?}/{tahun?}', [PerpuController::class, 'apiFilter']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/FrontOffice/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/FrontOffice/antrian/data', [AntrianController::class, 'getData'])->name('antrian.data');
    Route::post('/FrontOffice/antrian/call', [AntrianController::class, 'call'])->name('antrian.call');
    Route::post('/FrontOffice/antrian/end-call', [AntrianController::class, 'endCall'])->name('antrian.end-call');
    Route::post('/FrontOffice/antrian/kirim-pesan', [AntrianController::class, 'kirimPesan'])->name('antrian.kirim-pesan');
    Route::post('/FrontOffice/antrian/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');

    Route::get('/display', [QueueDisplayController::class, 'index'])->name('queue.display');
    // Route::get('/Profile', function() {
    //     return view('Profile.index', ['type_menu' => 'profile']);
    // })->name('profile');

    Route::resource('Profile', ProfileController::class)->only([
        'index', 'update'
    ]);
    // Add this route for real-time stats
    Route::get('/profile/stats', [ProfileController::class, 'getUserStats'])->name('profile.stats');
    Route::get('/profile/activities', [ProfileController::class, 'getRecentActivities'])->name('profile.activities');
    Route::get('/profile/get-rt-by-rw', [ProfileController::class, 'getRtByRw'])->name('profile.getRtByRw');

    // User Applications routes (hanya untuk user)
    Route::middleware(['cekrole:user,admin,Ketua RT,Ketua RW'])->prefix('Profile/')->group(function () {
        Route::get('/permohonan-saya', [UserApplicationController::class, 'index'])->name('user-applications.index');
        Route::get('/permohonan-saya/data', [UserApplicationController::class, 'getData'])->name('user-applications.getData');
        Route::get('/permohonan-saya/summary', [UserApplicationController::class, 'getSummary'])->name('user-applications.getSummary');
        Route::get('/permohonan-saya/{userApplication}', [UserApplicationController::class, 'show'])->name('user-applications.show');
        Route::get('/permohonan-saya/{userApplication}/preview-pdf', [UserApplicationController::class, 'previewPDF'])->name('user-applications.preview-pdf');
        Route::get('/permohonan-saya/{userApplication}/download-pdf', [UserApplicationController::class, 'downloadPDF'])->name('user-applications.download-pdf');
        Route::get('/permohonan-saya/activities', [UserApplicationController::class, 'getRecentActivities'])->name('user-applications.activities');
        // Route untuk redirect dari nomor surat ke detail
        Route::get('/permohonan-saya/nomor/{nomorSurat}', [UserApplicationController::class, 'showByNomor'])->name('user-applications.show-by-nomor');

        // Route untuk detail berdasarkan jenis dan reference_id
        Route::get('/permohonan-saya/{jenis}/{referenceId}', [UserApplicationController::class, 'showByReference'])->name('user-applications.show-by-reference');

        Route::get('/user-applications/all', [UserApplicationController::class, 'indexAll'])->name('user-applications.index-all');
        Route::get('/user-applications/summary-all', [UserApplicationController::class, 'getSummaryAll'])->name('user-applications.getSummaryAll');
        Route::get('/user-applications/data-all', [UserApplicationController::class, 'getDataAll'])->name('user-applications.getDataAll');
    });

    Route::get('/get-provinsi', [WilayahController::class, 'getProvinsi'])->name('get.provinsi');
    Route::get('/get-kota', [WilayahController::class, 'getKota'])->name('get.kota');
    Route::get('/get-kecamatan', [WilayahController::class, 'getKecamatan'])->name('get.kecamatan');
    Route::get('/get-kelurahan', [WilayahController::class, 'getKelurahan'])->name('get.kelurahan');
    Route::get('/get-rw', [WilayahController::class, 'getRw'])->name('get.rw');
    Route::get('/get-rt', [WilayahController::class, 'getRt'])->name('get.rt');



    // Route::get('Settings', function() {
    //     return view('Settings.General', ['type_menu' => 'Settings']);
    // })->name('Settings');

    Route::get('/Activities', [ActivitiesController::class, 'index'])->name('activities.index');
    Route::get('/Activities/stats', [ActivitiesController::class, 'stats'])->name('activities.stats');
    Route::get('/Activities/{id}', [ActivitiesController::class, 'show'])->name('activities.show');
    Route::delete('/Activities/{id}', [ActivitiesController::class, 'destroy'])->name('activities.destroy');

    Route::get('/Settings/General-Setting', [GeneralSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [GeneralSettingsController::class, 'update'])->name('settings.update');

});






// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
