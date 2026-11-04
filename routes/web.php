<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Audit\ActivityLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Branches\BranchesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Export\ExportController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{user}', [UserController::class, 'show'])->whereUuid('user')->name('user.show');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->whereUuid('user')->name('user.edit');
    Route::post('/user/{user}/edit', [UserController::class, 'update'])->whereUuid('user')->name('user.update');
    Route::get('/user/{user}/delete', [UserController::class, 'delete'])->whereUuid('user')->name('user.delete');
    Route::post('/user/{user}/delete', [UserController::class, 'destroy'])->whereUuid('user')->name('user.destroy');
    Route::get('/user/export', [ExportController::class, 'index'])->name('user.export');

    Route::get('/branches', [BranchesController::class, 'index'])->name('branches.index');
    Route::get('/branches/search', [BranchesController::class, 'search'])->name('branches.search');
    Route::get('/branches/create', [BranchesController::class, 'create'])->name('branches.create');
    Route::post('/branches/create', [BranchesController::class, 'store'])->name('branches.store');
    Route::get('/branches/{branches}', [BranchesController::class, 'show'])->whereUuid('branches')->name('branches.show');
    Route::get('/branches/{branches}/edit', [BranchesController::class, 'edit'])->whereUuid('branches')->name('branches.edit');
    Route::post('/branches/{branches}/edit', [BranchesController::class, 'update'])->whereUuid('branches')->name('branches.update');
    Route::get('/branches/{branches}/delete', [BranchesController::class, 'delete'])->whereUuid('branches')->name('branches.delete');
    Route::post('/branches/{branches}/delete', [BranchesController::class, 'destroy'])->whereUuid('branches')->name('branches.destroy');
    Route::get('/branches/export', [ExportController::class, 'index'])->name('branches.export');

    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.log.index');
    Route::get('/activity-log/{activityLog}', [ActivityLogController::class, 'show'])->whereUuid('activityLog')->name('activity.log.show');
    Route::get('/activity-log/export', [ExportController::class, 'index'])->name('activity.log.export');

    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [UserController::class, 'profileUpdate'])->name('user.profile.update');

    Route::get('/change/password', [UserController::class, 'changePassword'])->name('user.password');
    Route::post('/change/password', [UserController::class, 'changePasswordUpdate'])->name('user.password.update');

    Route::post('/export', [ExportController::class, 'store'])->name('export.store');
    Route::post('/export/{requestId}/batch', [ExportController::class, 'batch'])->where('requestId', '[A-Za-z0-9]{48}')->name('export.batch');
    Route::get('/export/{requestId}/download/{start}', [ExportController::class, 'download'])->where(['requestId' => '[A-Za-z0-9]{48}', 'start' => '[0-9]+'])->name('export.download');
    Route::delete('/export/{requestId}/cleanup', [ExportController::class, 'cleanup'])->name('export.cleanup');
    Route::get('/export/cleanups', [ExportController::class, 'cleanups'])->name('export.cleanups');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginStore'])->name('login.store');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerStore'])->name('register.store');

    Route::get('/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verify');
    Route::get('/verify/success', [AuthController::class, 'verifySuccess'])->name('verify.success');

    Route::get('/resend/verification', [AuthController::class, 'resendVerification'])->name('resend.verification');
    Route::post('/resend/verification', [AuthController::class, 'resendVerificationStore'])->name('resend.verification.store');

    Route::get('/forgot/password', [AuthController::class, 'forgot'])->name('forgot');
    Route::post('/forgot/password', [AuthController::class, 'forgotStore'])->name('forgot.store');

    Route::get('/reset/password/{token}/{email}', [AuthController::class, 'reset'])->name('reset.password');
    Route::post('/reset/password', [AuthController::class, 'resetStore'])->name('reset.password.store');
});


Route::get('/cc', function () {
    function hapusSemuaCache($folder, $kecuali) {
        $fileSystem = new Illuminate\Filesystem\Filesystem;

        $arFileYangDihapus = array_filter(glob($folder), function($file) use ($kecuali) {
            return false === strpos($file, $kecuali);
        });

        $fileSystem->delete($arFileYangDihapus);
    }

    hapusSemuaCache("../storage/framework/sessions/*", ".gitignore");
    hapusSemuaCache("../storage/framework/views/*", ".gitignore");

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');

    $cc1 = '../bootstrap/cache/config.php';
    $cc2 = '../bootstrap/cache/packages.php';
    $cc3 = '../bootstrap/cache/services.php';
    if (file_exists($cc1)) {
        unlink($cc1);
    }
    if (file_exists($cc2)) {
        unlink($cc2);
    }
    if (file_exists($cc3)) {
        unlink($cc3);
    }
    return 'Wait..<script>setTimeout(() => {window.location="/";},1000);</script>';
});

Route::fallback(function(){
    return view('errors/404');
});
