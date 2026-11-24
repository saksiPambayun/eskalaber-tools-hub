<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ToolsmanController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\FineSettingController as AdminFineSettingController;
use App\Http\Controllers\Admin\LogoSettingController as AdminLogoSettingController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Toolsman\DashboardController as ToolsmanDashboardController;
use App\Http\Controllers\Toolsman\LoanController as ToolsmanLoanController;
use App\Http\Controllers\Toolsman\FineController as ToolsmanFineController;
use App\Http\Controllers\Toolsman\FineSettingController as ToolsmanFineSettingController;
use App\Http\Controllers\Toolsman\LogoSettingController as ToolsmanLogoSettingController;
use App\Http\Controllers\Toolsman\PaymentMethodController as ToolsmanPaymentMethodController;
use App\Http\Controllers\Toolsman\UserController as ToolsmanUserController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ToolController as UserToolController;
use App\Http\Controllers\User\LoanController as UserLoanController;
use App\Http\Controllers\User\FineController as UserFineController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// ============================================
// LANDING PAGE
// ============================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Named dashboard untuk redirect scaffold Breeze.
Route::middleware(['auth'])->get('/dashboard', function () {
    return match (auth()->user()->role) {
        'SUPERADMIN' => redirect()->route('admin.dashboard'),
        'TOOLSMAN' => redirect()->route('toolsman.dashboard'),
        default => redirect()->route('user.dashboard'),
    };
})->name('dashboard');

// Password reset & verifikasi email (view Breeze yang sudah ada).
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);
});

// ==============================
// PUBLIC CATALOG (Tanpa Login)
// ==============================
Route::get('/catalog', [App\Http\Controllers\CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/detail/{id}', [App\Http\Controllers\CatalogController::class, 'detail'])->name('catalog.detail');
Route::get('/catalog/search', [App\Http\Controllers\CatalogController::class, 'search'])->name('catalog.search');

// ============================================
// AUTH ROUTES
// ============================================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('register.post');

// ============================================
// PROFILE ROUTES
// ============================================
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {    
    Route::get('/', function () {
        return view('profile.index');
    })->name('index');

    Route::get('/change-password', function () {
        return view('profile.change-password');
    })->name('change_password');

    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update_password');
    Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('update_photo');
    Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('delete_photo');

    // Endpoint standar Breeze (dipakai oleh scaffold test & view).
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->put('/password', [PasswordController::class, 'update'])->name('password.update');

// ============================================
// NOTIFICATION ROUTES  <-- TAMBAHKAN INI
// ============================================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::post('/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
});

// ============================================
// ADMIN ROUTES (SUPERADMIN)
// ============================================
Route::middleware(['auth', 'role:SUPERADMIN'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/add', [AdminUserController::class, 'add'])->name('add');
        Route::post('/create', [AdminUserController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [AdminUserController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/update/{id}', [AdminUserController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [AdminUserController::class, 'delete'])->name('delete');
    });

    // Toolsman Management
    Route::prefix('toolsmans')->name('toolsmans.')->group(function () {
        Route::get('/', [ToolsmanController::class, 'index'])->name('index');
        Route::get('/add', [ToolsmanController::class, 'add'])->name('add');
        Route::post('/create', [ToolsmanController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [ToolsmanController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [ToolsmanController::class, 'update'])->name('update');
        Route::post('/update/{id}', [ToolsmanController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [ToolsmanController::class, 'delete'])->name('delete');
    });

    // Departments
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/add', [DepartmentController::class, 'add'])->name('add');
        Route::post('/create', [DepartmentController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::post('/update/{id}', [DepartmentController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [DepartmentController::class, 'delete'])->name('delete');
    });

    // Places
    Route::prefix('places')->name('places.')->group(function () {
        Route::get('/', [PlaceController::class, 'index'])->name('index');
        Route::get('/add', [PlaceController::class, 'add'])->name('add');
        Route::post('/create', [PlaceController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [PlaceController::class, 'update'])->name('update');
        Route::post('/update/{id}', [PlaceController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [PlaceController::class, 'delete'])->name('delete');
    });

    // Types
    Route::prefix('types')->name('types.')->group(function () {
        Route::get('/', [TypeController::class, 'index'])->name('index');
        Route::get('/add', [TypeController::class, 'add'])->name('add');
        Route::post('/create', [TypeController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [TypeController::class, 'update'])->name('update');
        Route::post('/update/{id}', [TypeController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [TypeController::class, 'delete'])->name('delete');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/add', [CategoryController::class, 'add'])->name('add');
        Route::post('/create', [CategoryController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::post('/update/{id}', [CategoryController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });

    // Tools
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/', [ToolController::class, 'index'])->name('index');
        Route::get('/add', [ToolController::class, 'add'])->name('add');
        Route::post('/create', [ToolController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [ToolController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [ToolController::class, 'update'])->name('update');
        Route::post('/update/{id}', [ToolController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [ToolController::class, 'delete'])->name('delete');
    });

    // Loans
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [LoanController::class, 'detail'])->name('detail');
        Route::post('/update-status/{id}', [LoanController::class, 'updateStatus'])->name('update-status');
        Route::post('/test-return/{id}', [LoanController::class, 'testReturn'])->name('test-return');
        Route::delete('/delete/{id}', [LoanController::class, 'delete'])->name('delete');
        Route::get('/export-csv', [LoanController::class, 'exportCSV'])->name('export-csv');
    });

    // Fines
    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [FineController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [FineController::class, 'detail'])->name('detail');
        Route::post('/update-status/{id}', [FineController::class, 'updateStatus'])->name('update-status');
        Route::post('/verify/{id}', [FineController::class, 'verify'])->name('verify');
        Route::delete('/delete/{id}', [FineController::class, 'delete'])->name('delete');
        Route::get('/export-csv', [FineController::class, 'exportCSV'])->name('export-csv');
    });

    // Activity Logs
    Route::prefix('activity_logs')->name('activity_logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });

    // Logo Setting (global)
    Route::prefix('logo-setting')->name('logo-setting.')->group(function () {
        Route::get('/', [AdminLogoSettingController::class, 'index'])->name('index');
        Route::post('/update', [AdminLogoSettingController::class, 'update'])->name('update');
    });

    // Payment Methods
    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [AdminPaymentMethodController::class, 'index'])->name('index');
        Route::get('/add', [AdminPaymentMethodController::class, 'add'])->name('add');
        Route::post('/create', [AdminPaymentMethodController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdminPaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminPaymentMethodController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminPaymentMethodController::class, 'delete'])->name('delete');
    });

    // Fine Setting (global rate)
    Route::prefix('fine-setting')->name('fine-setting.')->group(function () {
        Route::get('/', [AdminFineSettingController::class, 'index'])->name('index');
        Route::post('/update', [AdminFineSettingController::class, 'update'])->name('update');
    });
});

// ============================================
// TOOLSMAN ROUTES
// ============================================
Route::middleware(['auth', 'role:TOOLSMAN'])->prefix('toolsman')->name('toolsman.')->group(function () {
    Route::get('/dashboard', [ToolsmanDashboardController::class, 'index'])->name('dashboard');

    // Loans
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [ToolsmanLoanController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [ToolsmanLoanController::class, 'detail'])->name('detail');
        Route::post('/approve/{id}', [ToolsmanLoanController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [ToolsmanLoanController::class, 'reject'])->name('reject');
        Route::post('/returned/{id}', [ToolsmanLoanController::class, 'returned'])->name('returned');
        Route::post('/test-return/{id}', [ToolsmanLoanController::class, 'testReturn'])->name('test-return');
    });

    // Fines
    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [ToolsmanFineController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [ToolsmanFineController::class, 'detail'])->name('detail');
        Route::post('/paid/{id}', [ToolsmanFineController::class, 'paid'])->name('paid');
        Route::post('/verify/{id}', [ToolsmanFineController::class, 'verify'])->name('verify');
        Route::get('/export', [ToolsmanFineController::class, 'exportExcel'])->name('export');
    });

    // Logo Setting (global)
    Route::prefix('logo-setting')->name('logo-setting.')->group(function () {
        Route::get('/', [ToolsmanLogoSettingController::class, 'index'])->name('index');
        Route::post('/update', [ToolsmanLogoSettingController::class, 'update'])->name('update');
    });

    // Payment Methods
    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [ToolsmanPaymentMethodController::class, 'index'])->name('index');
        Route::get('/add', [ToolsmanPaymentMethodController::class, 'add'])->name('add');
        Route::post('/create', [ToolsmanPaymentMethodController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [ToolsmanPaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ToolsmanPaymentMethodController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ToolsmanPaymentMethodController::class, 'delete'])->name('delete');
    });

    // Fine Setting (global rate)
    Route::prefix('fine-setting')->name('fine-setting.')->group(function () {
        Route::get('/', [ToolsmanFineSettingController::class, 'index'])->name('index');
        Route::post('/update', [ToolsmanFineSettingController::class, 'update'])->name('update');
    });

    // Profile toolsman
    Route::get('/profile', fn () => view('toolsman.profile'))->name('profile');
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/change-password', [ToolsmanUserController::class, 'changePassword'])->name('change_password');
        Route::post('/change-password', [ToolsmanUserController::class, 'doChangePassword'])->name('do_change_password');
    });
});

// ============================================
// USER ROUTES
// ============================================
Route::middleware(['auth', 'role:USER'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Tools
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/', [UserToolController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [UserToolController::class, 'detail'])->name('detail');
    });

    // Loans
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [UserLoanController::class, 'index'])->name('index');
        Route::get('/add', [UserLoanController::class, 'add'])->name('add');
        Route::post('/create', [UserLoanController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [UserLoanController::class, 'detail'])->name('detail');
        Route::post('/cancel/{id}', [UserLoanController::class, 'cancel'])->name('cancel');
    });

    // Fines
    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [UserFineController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [UserFineController::class, 'detail'])->name('detail');
        Route::post('/pay/{id}', [UserFineController::class, 'pay'])->name('pay');
    });

    // Profile user
    Route::get('/profile', fn () => view('user.profile'))->name('profile');
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/change-password', [UserUserController::class, 'changePassword'])->name('change_password');
        Route::post('/change-password', [UserUserController::class, 'doChangePassword'])->name('do_change_password');
    });
});


