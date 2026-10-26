<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;

Route::post('/register',[AuthController::class,'register']);

Route::post('/login',[AuthController::class,'login']);

Route::post('/admin/login',[AuthController::class,'adminLogin']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Admin-only endpoints

Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->group(function(){
        Route::get('/dashboard',function(Request $request){
            return response()->json([
                'message' => 'Welcome Admin',
                'user'    =>  $request->user()->only([
                        'id','name', 'email','role'
                ]),
            ]);
        });
    });

 // Customer-only endpoints
 
 Route::middleware(['auth:sanctum','role:customer'])
    ->prefix('customer')
    ->group(function(){
        Route::get('/dashboard', function(Request $request){
            return response()->json([
                'message' => 'Welcome Customer',
                'user' => $request->user()->only([
                        'id','name','email','role'
                ]),
            ]);
        });
    });


 Route::middleware('auth:sanctum')->group(function(){
    Route::get('/profile',[ProfileController::class, 'show']);
    Route::put('/profile',[ProfileController::class,'update']);
 });

 Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');


Route::post('/forgot-password', [
    AuthController::class,
    'forgotPassword',
]);

Route::post('/reset-password', [
    AuthController::class,
    'resetPassword',
]);


