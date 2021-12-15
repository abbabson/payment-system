<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Unauthenticated Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/transfer', [PaymentController::class, 'transferReceipt']);
    Route::get('/history', [PaymentController::class, 'customerHistory']);
});
