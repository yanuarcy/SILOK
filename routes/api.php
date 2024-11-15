<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ApiLoginController;
use App\Http\Controllers\Api\Auth\ApiRegisterController;



Route::post('login', [ApiLoginController::class, 'login']);
Route::post('register', [ApiRegisterController::class, 'register']);

Route::get('/', function () {
    return 'API';
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
