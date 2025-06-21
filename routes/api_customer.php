<?php

use App\Layers\Presentation\Controllers\Customer\CategoryController;
use App\Layers\Presentation\Controllers\Customer\MenuItemController;
use App\Layers\Presentation\Controllers\Customer\OrderController;
use App\Layers\Presentation\Controllers\Customer\SeatController;
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

// 客管理
Route::group(['prefix' => 'seats'], function () {
    Route::get('/{seat_id}/customers', [SeatController::class, 'show']);
    Route::post('/{seat_id}/customers', [SeatController::class, 'store']);
    Route::put('/closing', [SeatController::class, 'update']);
});

// 顧客用メニューアイテム
Route::group(['prefix' => 'menu_items'], function () {
    Route::get('/', [MenuItemController::class, 'index']);
    Route::get('/{menu_item_id}', [MenuItemController::class, 'show']);
});

// 顧客用カテゴリー
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
});

// 顧客用注文
Route::group(['prefix' => 'orders'], function () {
    Route::get('/', [OrderController::class, 'index']);
    // フロントのstoreで保持しているidをもとに注文内容を取得するのでリクエストが複雑になることが予測されるためPOSTで実装する
    Route::post('/confirm', [OrderController::class, 'confirm']);
    Route::post('/', [OrderController::class, 'store']);
});
