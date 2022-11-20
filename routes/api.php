<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// get produk
Route::get('/product', [ProductController::class, 'index']);
Route::get('/keranjang', [KeranjangController::class, 'index']);
Route::get('/diskon', [DiskonController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->group(function () {
        // product route
        Route::post('/product/{product}', [ProductController::class, 'update']);
        Route::resource('/product', ProductController::class)->except('index', 'update')->middleware('admin');

        // keranjang route
        Route::resource('/keranjang', KeranjangController::class)->except('index')->middleware('admin');

        // keranjang diskon
        Route::resource('/diskon', DiskonController::class)->except('index')->middleware('admin');
    });

    Route::get('/check', function () {
        return "hai";
    })->middleware('test');
});
