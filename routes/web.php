<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Cashflow\UmkRekapController;
use App\Http\Controllers\Cashflow\DashboardController;
use App\Http\Controllers\Cashflow\UmkRequestController;
use App\Http\Controllers\Cashflow\TransactionController;
use App\Http\Controllers\Cashflow\UmkPengajuanController;
use App\Http\Controllers\Cashflow\UmkRealisasiController;
use App\Http\Controllers\Cashflow\UmkRealizationController;
use App\Http\Controllers\Cashflow\PengaturanController;
use App\Http\Controllers\Cashflow\KegiatanPengajuanController;
use App\Http\Controllers\Cashflow\KegiatanRealisasiController;
use App\Http\Controllers\Cashflow\KegiatanRekapController;
use App\Http\Controllers\Cashflow\ReportCashflowController;
use App\Http\Controllers\Cashflow\ReportBankController;
use App\Http\Controllers\Cashflow\ProfileController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Optional register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// dashboard pages
// Route::get('/', function () {
//     return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');
Route::get('/', function () {
    return auth()->check() ? redirect('/persi/dashboard') : redirect('/login');
});

// // calender pages
// Route::get('/calendar', function () {
//     return view('pages.calender', ['title' => 'Calendar']);
// })->name('calendar');

// // profile pages
// Route::get('/profile', function () {
//     return view('pages.profile', ['title' => 'Profile']);
// })->name('profile');

// // form pages
// Route::get('/form-elements', function () {
//     return view('pages.form.form-elements', ['title' => 'Form Elements']);
// })->name('form-elements');

// // tables pages
// Route::get('/basic-tables', function () {
//     return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
// })->name('basic-tables');

// // pages

// Route::get('/blank', function () {
//     return view('pages.blank', ['title' => 'Blank']);
// })->name('blank');

// // error pages
// Route::get('/error-404', function () {
//     return view('pages.errors.error-404', ['title' => 'Error 404']);
// })->name('error-404');

// // chart pages
// Route::get('/line-chart', function () {
//     return view('pages.chart.line-chart', ['title' => 'Line Chart']);
// })->name('line-chart');

// Route::get('/bar-chart', function () {
//     return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
// })->name('bar-chart');


// // authentication pages
// Route::get('/signin', function () {
//     return view('pages.auth.signin', ['title' => 'Sign In']);
// })->name('signin');

// Route::get('/signup', function () {
//     return view('pages.auth.signup', ['title' => 'Sign Up']);
// })->name('signup');

// // ui elements pages
// Route::get('/alerts', function () {
//     return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
// })->name('alerts');

// Route::get('/avatars', function () {
//     return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
// })->name('avatars');

// Route::get('/badge', function () {
//     return view('pages.ui-elements.badges', ['title' => 'Badges']);
// })->name('badges');

// Route::get('/buttons', function () {
//     return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
// })->name('buttons');

// Route::get('/image', function () {
//     return view('pages.ui-elements.images', ['title' => 'Images']);
// })->name('images');

// Route::get('/videos', function () {
//     return view('pages.ui-elements.videos', ['title' => 'Videos']);
// })->name('videos');

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard (dinamis)
    Route::get('/persi/dashboard', [DashboardController::class, 'index'])->name('persi.dashboard');

    // Transaksi (dinamis)
    Route::prefix('persi/transaksi')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('persi.trx.index');

        Route::get('/penerimaan', [TransactionController::class, 'createIncome'])->name('persi.trx.penerimaan');
        Route::post('/penerimaan', [TransactionController::class, 'storeIncome'])->name('persi.trx.penerimaan.store');

        Route::get('/pengeluaran', [TransactionController::class, 'createExpense'])->name('persi.trx.pengeluaran');
        Route::post('/pengeluaran', [TransactionController::class, 'storeExpense'])->name('persi.trx.pengeluaran.store');
    });

    Route::prefix('persi/umk')->group(function () {
        // PENGAJUAN
        Route::get('/pengajuan', [UmkPengajuanController::class, 'index'])->name('persi.umk.pengajuan');
        Route::post('/pengajuan', [UmkPengajuanController::class, 'store'])->name('persi.umk.pengajuan.store');

        // REALISASI
        Route::get('/realisasi', [UmkRealisasiController::class, 'index'])->name('persi.umk.realisasi');

        // ✅ ADD: halaman create (detail UMK)
        Route::get('/realisasi/{umk}/create', [UmkRealisasiController::class, 'create'])->name('persi.umk.realisasi.create');

        // store detail realisasi
        Route::post('/realisasi/{umk}', [UmkRealisasiController::class, 'store'])->name('persi.umk.realisasi.store');

        // close
        Route::post('/realisasi/{umk}/close', [UmkRealisasiController::class, 'close'])->name('persi.umk.realisasi.close');

        // REKAP
        Route::get('/rekap', [UmkRekapController::class, 'index'])->name('persi.umk.rekap');
    });

    Route::prefix('persi/kegiatan')->group(function () {
        Route::get('/pengajuan', [KegiatanPengajuanController::class, 'index'])->name('persi.kegiatan.pengajuan');
        Route::post('/pengajuan', [KegiatanPengajuanController::class, 'store'])->name('persi.kegiatan.pengajuan.store');

        Route::get('/realisasi', [KegiatanRealisasiController::class, 'index'])->name('persi.kegiatan.realisasi');
        Route::get('/realisasi/{kegiatan}/create', [KegiatanRealisasiController::class, 'create'])->name('persi.kegiatan.realisasi.create');
        Route::get('/realisasi/{kegiatan}/print', [KegiatanRealisasiController::class, 'print'])->name('persi.kegiatan.realisasi.print');
        Route::post('/realisasi/{kegiatan}', [KegiatanRealisasiController::class, 'store'])->name('persi.kegiatan.realisasi.store');
        Route::post('/realisasi/{kegiatan}/close', [KegiatanRealisasiController::class, 'close'])->name('persi.kegiatan.realisasi.close');

        Route::get('/rekap', [KegiatanRekapController::class, 'index'])->name('persi.kegiatan.rekap');
    });


    Route::prefix('persi/report')->group(function () {
        Route::get('/cashflow', [ReportCashflowController::class, 'index'])->name('persi.report.cashflow');
        Route::get('/bank', [ReportBankController::class, 'index'])->name('persi.report.bank');
        Route::view('/kas-kecil', 'pages.persi.report.kas-kecil')->name('persi.report.kas_kecil');
        Route::view('/umk', 'pages.persi.report.umk')->name('persi.report.umk');
    });

    Route::get('/persi/profile', [ProfileController::class, 'edit'])->name('persi.profile');
    Route::put('/persi/profile', [ProfileController::class, 'update'])->name('persi.profile.update');
    Route::prefix('persi/pengaturan')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('persi.pengaturan');

        Route::post('/coa', [PengaturanController::class, 'storeCoa'])->name('persi.pengaturan.coa.store');
        Route::get('/coa/{coa}/edit', [PengaturanController::class, 'editCoa'])->name('persi.pengaturan.coa.edit');
        Route::put('/coa/{coa}', [PengaturanController::class, 'updateCoa'])->name('persi.pengaturan.coa.update');
        Route::post('/coa/{coa}/toggle', [PengaturanController::class, 'toggleCoa'])->name('persi.pengaturan.coa.toggle');

        Route::post('/member-classes', [PengaturanController::class, 'storeMemberClass'])->name('persi.pengaturan.member_classes.store');
        Route::get('/member-classes/{memberClass}/edit', [PengaturanController::class, 'editMemberClass'])->name('persi.pengaturan.member_classes.edit');
        Route::put('/member-classes/{memberClass}', [PengaturanController::class, 'updateMemberClass'])->name('persi.pengaturan.member_classes.update');
        Route::delete('/member-classes/{memberClass}', [PengaturanController::class, 'destroyMemberClass'])->name('persi.pengaturan.member_classes.destroy');

        Route::post('/members', [PengaturanController::class, 'storeMember'])->name('persi.pengaturan.members.store');
        Route::get('/members/{member}/edit', [PengaturanController::class, 'editMember'])->name('persi.pengaturan.members.edit');
        Route::put('/members/{member}', [PengaturanController::class, 'updateMember'])->name('persi.pengaturan.members.update');
        Route::delete('/members/{member}', [PengaturanController::class, 'destroyMember'])->name('persi.pengaturan.members.destroy');
    });
});
