<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
//API route for login user
Route::post('/login', [AuthController::class, 'login']);

//Protecting
Route::group(['middleware' => ['auth:sanctum']], function (){
    //Produk
    Route::get('/produk', [ProdukController::class, 'index']);
    Route::get('/produk/{id}', [ProdukController::class, 'show']);

    //Route Middleware User
    Route::middleware(['user'])->group(function () {
        //Trasaksi
        Route::get('/mytrans', [TransaksiController::class, 'show']);
        Route::post('/trans', [TransaksiController::class, 'store']);
    });

    //Payment
    Route::get('/pay', [PaymentController::class, 'index']);
    Route::get('/pay/{id}', [PaymentController::class, 'show']);

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::middleware('admin')->group(function () {
        //Route Melihat User
        Route::get('/auth/{id}', [AuthController::class, 'show']);
        //Route Produk
        Route::post('/produk', [ProdukController::class, 'store']);
        Route::post('/produk/{id}', [ProdukController::class, 'update']);
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

        //Route Transaksi
        Route::get('/trans', [TransaksiController::class, 'index']);
        Route::post('/trans/{id}', [TransaksiController::class, 'update']);

        //Route Payment
        Route::post('/pay', [PaymentController::class, 'store']);
        Route::put('/pay/{id}', [PaymentController::class, 'update']);
    });
});