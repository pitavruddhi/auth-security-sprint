
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');

    Route::get('/forgot-password', [AuthController::class,
        'showForgotPassword',
    ])->name('password.request');

    Route::post('/forgot-password', [AuthController::class,
        'sendResetLink',
    ])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class,
    'showResetPassword',
    ])->name('password.reset');

    Route::post('/reset-password', [AuthController::class,
        'resetPassword',
    ])->name('password.update');
    
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');    

});

Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin/dashboard',function(){
        return view('dashboard.admin');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('dashboard.customer');
    })->name('customer.dashboard');
});

// Existing category CRUD routes — preserved.
Route::resource('categories', CategoryController::class)
    ->except(['show']);
