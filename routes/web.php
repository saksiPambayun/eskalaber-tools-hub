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
use App\Http\Controllers\Toolsman\DashboardController as ToolsmanDashboardController;
use App\Http\Controllers\Toolsman\LoanController as ToolsmanLoanController;
use App\Http\Controllers\Toolsman\FineController as ToolsmanFineController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ToolController as UserToolController;
use App\Http\Controllers\User\LoanController as UserLoanController;
use App\Http\Controllers\User\FineController as UserFineController;
use Illuminate\Support\Facades\Route;

// ============================================
// LANDING PAGE
// ============================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

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
        Route::get('/generate-qr/{id}', [ToolController::class, 'generateQR'])->name('generate-qr');
        Route::get('/preview-qr/{id}', [ToolController::class, 'previewQR'])->name('preview-qr');
    });

    // Loans
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [LoanController::class, 'detail'])->name('detail');
        Route::post('/update-status/{id}', [LoanController::class, 'updateStatus'])->name('update-status');
        Route::delete('/delete/{id}', [LoanController::class, 'delete'])->name('delete');
        Route::get('/export-csv', [LoanController::class, 'exportCSV'])->name('export-csv');
    });

    // Fines
    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [FineController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [FineController::class, 'detail'])->name('detail');
        Route::post('/update-status/{id}', [FineController::class, 'updateStatus'])->name('update-status');
        Route::delete('/delete/{id}', [FineController::class, 'delete'])->name('delete');
        Route::get('/export-csv', [FineController::class, 'exportCSV'])->name('export-csv');
    });

    // Activity Logs
    Route::prefix('activity_logs')->name('activity_logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
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
    });

    // Fines
    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [ToolsmanFineController::class, 'index'])->name('index');
        Route::post('/paid/{id}', [ToolsmanFineController::class, 'paid'])->name('paid');
        Route::get('/export', [ToolsmanFineController::class, 'exportExcel'])->name('export');
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
    });
});

// Loans Export
Route::get('/loans/export', [LoanController::class, 'exportExcel'])->name('loans.export');

// Fines Export
Route::get('/fines/export', [FineController::class, 'exportExcel'])->name('fines.export');
