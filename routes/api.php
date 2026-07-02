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
    Route::get('/products', 'index');
    Route::post('/products/store', 'store')->middleware('auth:sanctum');
    Route::get('/products/{id}', 'show');
    Route::put('/products/update/{id}', 'update')->middleware('auth:sanctum');
    Route::delete('/products/delete/{id}', 'destroy')->middleware('auth:sanctum');
});
