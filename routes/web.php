<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoketController;
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

Route::get('/About', [AboutController::class, 'index'])->name('About');
Route::get('/Pegawai', [PegawaiController::class, 'index'])->name('Pegawai');
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

Route::resource('/Master-Data/Member', MemberController::class);
Route::get('members-data', [MemberController::class, 'getData'])->name('members.data');

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
Route::get('/Dashboard/Front-Office', function () {
    return view('admin.dashboard.front-office', ['type_menu' => 'dashboard']);
})->name('Dashboard.FrontOffice')->middleware(['auth']);


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

    Route::resource('sub-layanan', SubLayananController::class);

    Route::get('/layanan-item/{item}/edit', [LayananItemController::class, 'edit'])->name('layanan-item.edit');
    Route::put('/layanan-item/{item}', [LayananItemController::class, 'update'])->name('layanan-item.update');
    Route::delete('/layanan-item/{item}', [LayananItemController::class, 'destroy'])->name('layanan-item.destroy');

    Route::resource('registration-options', RegistrationOptionController::class);

    Route::resource('applicant-types', ApplicantTypeController::class);
});



// Route::get('/Master-Data/Member', [MemberController::class, 'index'])->name('masterdata.member')->middleware(['auth']);
Route::get('/Master-Data/Data-Layanan/Layanan', function () {
    return view('admin.masterdata.Layanan.index', ['type_menu' => 'master-data']);
})->name('masterdata.layanan');

Route::get('/Master-Data/Data-Layanan/sub-layanan', function () {
    return view('admin.masterdata.Layanan.sub-layanan-index', ['type_menu' => 'master-data']);
})->name('masterdata.sub-layanan');

Route::get('/Master-Data/Data-Layanan/layanan-item', function () {
    return view('admin.masterdata.Layanan.layanan-item-index', ['type_menu' => 'master-data']);
})->name('masterdata.layanan-item');

Route::get('/Master-Data/Data-Layanan/kategori-pendaftaran', function () {
    return view('admin.masterdata.Layanan.kategori-pendaftaran', ['type_menu' => 'master-data']);
})->name('masterdata.kategori-pendaftaran');

Route::get('/Master-Data/Data-Layanan/kategori-pemohon', function () {
    return view('admin.masterdata.Layanan.kategori-pemohon', ['type_menu' => 'master-data']);
})->name('masterdata.kategori-pemohon');

Route::get('/Master-Data/Loket', function () {
    return view('admin.masterdata.Loket.data-loket', ['type_menu' => 'master-data']);
})->name('masterdata.loket');

Route::get('/Master-Data/ApiWhatsapp', function () {
    return view('admin.masterdata.data-apiWhatsapp', ['type_menu' => 'master-data']);
})->name('masterdata.apiWhatsapp');

Route::get('/Master-Data/Antarmuka', function () {
    return view('admin.masterdata.data-antarmuka', ['type_menu' => 'master-data']);
})->name('masterdata.antarmuka');

Route::get('/Master-Data/Pemohon', function () {
    return view('admin.masterdata.data-pemohon', ['type_menu' => 'master-data']);
})->name('masterdata.pemohon');

Route::middleware(['auth'])->group(function () {
    Route::get('/FrontOffice/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/FrontOffice/antrian/data', [AntrianController::class, 'getData'])->name('antrian.data');
    Route::post('/FrontOffice/antrian/call', [AntrianController::class, 'call'])->name('antrian.call');
    Route::post('/FrontOffice/antrian/end-call', [AntrianController::class, 'endCall'])->name('antrian.end-call');
    Route::post('/FrontOffice/antrian/kirim-pesan', [AntrianController::class, 'kirimPesan'])->name('antrian.kirim-pesan');
    Route::post('/FrontOffice/antrian/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');

    // Route::get('/Profile', function() {
    //     return view('Profile.index', ['type_menu' => 'profile']);
    // })->name('profile');

    Route::resource('Profile', ProfileController::class)->only([
        'index', 'update'
    ]);

    Route::get('/get-provinsi', [WilayahController::class, 'getProvinsi'])->name('get.provinsi');
    Route::get('/get-kota', [WilayahController::class, 'getKota'])->name('get.kota');
    Route::get('/get-kecamatan', [WilayahController::class, 'getKecamatan'])->name('get.kecamatan');
    Route::get('/get-kelurahan', [WilayahController::class, 'getKelurahan'])->name('get.kelurahan');
    Route::get('/get-rw', [WilayahController::class, 'getRw'])->name('get.rw');
    Route::get('/get-rt', [WilayahController::class, 'getRt'])->name('get.rt');

    Route::resource('lokets', LoketController::class);
    Route::get('lokets-data', [LoketController::class, 'getData'])->name('lokets.data');

});






// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
