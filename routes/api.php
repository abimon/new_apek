<?php

use App\Http\Controllers\KamandeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(KamandeController::class)->group(function () {
    Route::get('/kamandes', 'index');
    Route::post('/kamandes', 'store');
    Route::get('/kamandes/{kamande}', 'show');
    Route::put('/kamandes/{kamande}', 'update');
    Route::delete('/kamandes/{kamande}', 'destroy');
});
