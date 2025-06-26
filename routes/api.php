<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ApiLoginController;
use App\Http\Controllers\Api\Auth\ApiRegisterController;
use App\Http\Controllers\Api\QueueApiController;
use App\Http\Controllers\Api\AntarmukaApiController;



Route::post('login', [ApiLoginController::class, 'login']);
Route::post('register', [ApiRegisterController::class, 'register']);

Route::prefix('queue')->group(function () {
    Route::post('/add', [QueueApiController::class, 'addToQueue']);
    Route::get('/next', [QueueApiController::class, 'getNext']);
    Route::post('/complete', [QueueApiController::class, 'completeQueue']);
    Route::get('/next-list', [QueueApiController::class, 'getNextList']);
    Route::get('/stats', [QueueApiController::class, 'getStats']);
});

Route::prefix('antarmuka')->group(function () {
    Route::get('/active-video', [App\Http\Controllers\Api\AntarmukaApiController::class, 'getActiveVideo']);
    Route::get('/all-videos', [App\Http\Controllers\Api\AntarmukaApiController::class, 'getAllVideos']);
});

Route::get('/', function () {
    return 'API';
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
