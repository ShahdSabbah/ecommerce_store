<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// مسار إنشاء حساب جديد
Route::post('/register', [AuthController::class, 'register']);
//  مسار عام لتسجيل الدخول 
Route::post('/login', [AuthController::class, 'login']);
//  مسار عام لعرض المنتجات
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
// مسارات الأقسام (متاحة للعموم)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);


//  مجموعة المسارات المحمية (تتطلب وجود Token صالح)
Route::middleware('auth:sanctum')->group(function () {

    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);
    // مسار لارجاع بيانات المستخدم صاحب الـ Token الحالي
    Route::get('/user', [AuthController::class, 'user']);

    // مسارات سلة التسوق الكاملة
    Route::get('/cart', [CartController::class, 'index']);             // عرض محتويات السلة
    Route::post('/cart', [CartController::class, 'store']);            // إضافة منتج للسلة مع فحص المخزون
    Route::put('/cart/{id}', [CartController::class, 'update']);       // تحديث كمية منتج في السلة
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);   // حذف منتج معين من السلة
    Route::delete('/cart', [CartController::class, 'clear']);          // تفريغ السلة بالكامل

    // مسارات الطلبات
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});