<?php

use App\Http\Controllers\KamandeController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(KamandeController::class)->group(function () {
    Route::get('/school', 'index');
    Route::post('/school/store', 'store');
    Route::get('/school/{id}', 'show');
    Route::put('/school/update/{id}', 'update');
    Route::delete('/school/delete/{id}', 'destroy');
});
Route::controller(ShopController::class)->group(function () {
    Route::get('/shop', 'index');
    Route::post('/shop/store', 'store');
    Route::get('/shop/{id}', 'show');
    Route::put('/shop/update/{id}', 'update');
    Route::delete('/shop/delete/{id}', 'destroy');
});
