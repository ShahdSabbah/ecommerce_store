<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrivateDocumentController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//  1. الواجهة الرئيسية للمتجر (مع الفلترة والأقسام الفريدة)
Route::get('/', function (Request $request) {
    // جلب الأقسام الفريدة بدون تكرار الأسماء
    $categories = Category::select('id', 'name')->get()->unique('name');

    // استعلام المنتجات مع دعم الفلترة بالقسم
    $query = Product::with('category')->latest();

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $products = $query->get();

    return view('welcome_store', compact('categories', 'products'));
})->name('home');


//  2. مسار التوجيه بعد تسجيل الدخول (Dashboard)
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        $categoriesCount = Category::count();
        $totalProducts   = Product::count();
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'pending')->count();
        $totalSales      = Order::where('status', 'completed')->sum('total_price');

        return view('dashboard', compact(
            'categoriesCount', 
            'totalProducts', 
            'totalOrders', 
            'pendingOrders',
            'totalSales'
        ));
    }

    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');


//  3. مسارات لوحة التحكم والإدارة (متاحة فقط للأدمن)
Route::middleware(['auth', 'admin'])->group(function () {

    // إدارة الأقسام والمنتجات
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    // إدارة الطلبات
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

});


// 👑 4. مسارات السوبر أدمن (متاحة حصرياً للسوبر أدمن)
Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::resource('users', UserController::class);

    // إدارة الوثائق والفواتير الخاصة المحمية
    Route::resource('private-documents', PrivateDocumentController::class)->except(['edit', 'update']);
    Route::get('private-documents/{private_document}/view', [PrivateDocumentController::class, 'show'])->name('private-documents.view');
    Route::get('private-documents/{private_document}/download', [PrivateDocumentController::class, 'download'])->name('private-documents.download');
});


// 👤 5. مسارات الملف الشخصي (متاحة لكل مستخدم مسجل دخوله)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // عرض صفحة تعبئة بيانات العنوان وتأكيد الطلب
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // معالجة وحفظ الطلب نهائياً
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');


//  مسارات المصادقة الجاهزة من Laravel Breeze
require __DIR__.'/auth.php';