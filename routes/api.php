<?php

use App\Http\Controllers\KamandeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(KamandeController::class)->group(function () {
    Route::get('/school', 'index');
    Route::post('/school', 'store');
    Route::get('/school/{id}', 'show');
    Route::put('/school/{id}', 'update');
    Route::delete('/school/{id}', 'destroy');
});
