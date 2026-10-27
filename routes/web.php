
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ProductController;

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


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard.admin');
        })->name('dashboard');

         // Category management
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::get('products/trash', [ProductController::class, 'trash'])
            ->name('products.trash');

        Route::patch('products/{product}/restore', [ProductController::class, 'restore'])
            ->name('products.restore');

        Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])
            ->name('products.forceDelete');    

        // Product Management: index, create, store, edit, update, destroy
        Route::resource('products', ProductController::class)
            ->except(['show']);
    });

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('dashboard.customer');
    })->name('customer.dashboard');
});


Route::get('/catalog', [ProductController::class, 'catalog'])
    ->name('catalog.index');

Route::get('/catalog/{product:slug}', [ProductController::class, 'publicShow'])
    ->name('catalog.show');


