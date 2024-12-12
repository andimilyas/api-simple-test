<?php

use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/items', ItemController::class)->middleware('auth:sanctum');
Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);