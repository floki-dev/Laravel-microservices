<?php

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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return 'API is working!';
});

// Common
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::group([
    'middleware' => 'auth:api', // Unauthenticated if header Accept application/json
], function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::put('users/info', [AuthController::class, 'updateInfo']);
    Route::put('users/password', [AuthController::class, 'updatePassword']);
});

// Admin
Route::group([
    'middleware' => ['auth:api', 'scope:admin'], // Unauthenticated if header Accept application/json
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('chart', [DashboardController::class, 'chart']);
    Route::post('upload', [ImageController::class, 'upload']);
    Route::get('export', [OrderController::class, 'export']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class)->only('index', 'show');
    Route::apiResource('permissions', PermissionController::class)->only('index');
});

// Influencer
Route::group([
    'prefix' => 'influencer',
    'namespace' => 'Influencer'
], function () {
    Route::get('products', [ProductController::class, 'index']);
});
