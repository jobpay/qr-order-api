<?php

use App\Http\Controllers\Api\PushSubscriptionController;
use App\Http\Controllers\Api\QrCodeController;
use App\Layers\Presentation\Controllers\Shop\CategoryController;
use App\Layers\Presentation\Controllers\Shop\MenuItemController;
use App\Layers\Presentation\Controllers\Shop\OrderController;
use App\Layers\Presentation\Controllers\Shop\SaleController;
use App\Layers\Presentation\Controllers\Shop\SeatController;
use App\Layers\Presentation\Controllers\Shop\ShopController;
use App\Layers\Presentation\Controllers\Shop\UserController;
use App\Models\User;
use App\Notifications\WebPushDemo;
use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = User::select(
        'users.*',
        'subscriptions.stripe_id',
        'subscriptions.stripe_status',
        'subscriptions.ends_at',
    )
        ->Where('users.id', $request->user()->id)
        ->leftJoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')
        ->first();

    return $user;
});

// 契約
Route::post('/user/subscribe', function (Request $request) {
    // ユーザーの認証チェック
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // サブスクリプションの作成
    $subscription = $user->newSubscription(
        env('STRIPE_SUBSCRIPTION_ID'),
        env('STRIPE_SUBSCRIPTION_PRICE_ID')
    )->create($request->paymentMethodId);

    return response()->json(['success' => 'サブスクリプション登録が完了しました。']);
});

// 解約
Route::post('/user/unsubscribe', function (Request $request) {
    // ユーザーの認証チェック
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    if ($user->subscription(env('STRIPE_SUBSCRIPTION_ID'))->active()) {
        $user->subscription(env('STRIPE_SUBSCRIPTION_ID'))->cancel();

        return response()->json([
            'message' => 'サブスクリプションは請求期間終了時にキャンセルされます。',
        ]);
    }

    // 成功レスポンスの送信
    return response()->json(['error' => '解約処理に失敗しました。管理者にお問合せください。'], 400);
});

// 支払い情報の変更
Route::post('/user/subscribe/change', function (Request $request) {
    // ユーザーの認証チェック
    /** @var User $user */
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $paymentMethod = $user->addPaymentMethod($request->paymentMethodId);

    $user->updateDefaultPaymentMethod($paymentMethod->id);

    return response()->json(['success' => '支払い情報の変更が完了しました。']);
});

// QRコード生成
Route::get('/qr-code/generate', [QrCodeController::class, 'generate']);

// プッシュ通知購読
Route::post('/subscribe', [PushSubscriptionController::class, 'store']);

// プッシュ通知テスト
Route::post('/test-push', function () {
    $user = User::find(1);
    $user->notify(new WebPushDemo);
    return response()->json(['message' => 'Test push notification sent.']);
});

// 店舗管理
Route::group(['prefix' => 'stores'], function () {
    Route::post('/', [ShopController::class, 'store']);
    Route::get('/', [ShopController::class, 'show']);
    Route::post('/update', [ShopController::class, 'update']); // 更新APIだが、multipart/form-data を含むのでPUTではなくPOSTを使用
});

// 座席管理
Route::group(['prefix' => 'seats'], function () {
    Route::get('/', [SeatController::class, 'index']);
    Route::post('/', [SeatController::class, 'store']);
    Route::get('/{seat_id}', [SeatController::class, 'show']);
    Route::put('/{seat_id}', [SeatController::class, 'update']);
    Route::delete('/{seat_id}', [SeatController::class, 'destroy']);
});

// メニュー管理
Route::group(['prefix' => 'menu_items'], function () {
    Route::get('/', [MenuItemController::class, 'index']);
    Route::post('/', [MenuItemController::class, 'store']);
    Route::get('/{menu_item_id}', [MenuItemController::class, 'show']);
    Route::post('/{menu_item_id}', [MenuItemController::class, 'update']); // 更新APIだが、multipart/form-data を含むのでPUTではなくPOSTを使用
    Route::delete('/{menu_item_id}', [MenuItemController::class, 'destroy']);
});

// カテゴリー管理
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{category_id}', [CategoryController::class, 'update']);
    Route::delete('/{category_id}', [CategoryController::class, 'destroy']);
});

// 注文管理
Route::group(['prefix' => 'orders'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{order_id}', [OrderController::class, 'show']);
    Route::put('/{order_id}', [OrderController::class, 'update']);
});

// メンバー管理
Route::group(['prefix' => 'users'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user_id}', [UserController::class, 'show']);
    Route::put('/{user_id}', [UserController::class, 'update']);
    Route::delete('/{user_id}', [UserController::class, 'destroy']);
});

// 売上管理
Route::group(['prefix' => 'sales'], function () {
    Route::get('/', [SaleController::class, 'index']);
    Route::post('/', [SaleController::class, 'store']);
    Route::put('/{sale_id}', [SaleController::class, 'update']);
    Route::delete('/{sale_id}', [SaleController::class, 'destroy']);
});
