<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrivateDocumentController;
use App\Http\Controllers\NotificationController;
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

// 🌐 1. مسار تبديل اللغة وحفظها في الـ Cookie والـ Session
Route::get('/lang/{locale}', function (Request $request, $locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session()->put('locale', $locale);
        session()->save();
        cookie()->queue(cookie('locale', $locale, 60 * 24 * 365, null, null, false, false));
    }
    return redirect()->back();
})->name('lang.switch');


// 🛍️ 2. الواجهة الرئيسية للمتجر (مع الفلترة والأقسام)
Route::get('/', function (Request $request) {
    $categories = Category::select('id', 'name')->get()->unique('name');

    $query = Product::with('category')->latest();
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    $products = $query->get();

    return view('welcome_store', compact('categories', 'products'));
})->name('home');


// 📊 3. مسار التوجيه بعد تسجيل الدخول (Dashboard)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
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


// 🛡️ 4. مسارات لوحة التحكم والإدارة (متاحة فقط للأدمن)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});


// 👑 5. مسارات السوبر أدمن (متاحة حصرياً للسوبر أدمن)
Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('private-documents', PrivateDocumentController::class)->except(['edit', 'update']);
    Route::get('private-documents/{private_document}/view', [PrivateDocumentController::class, 'show'])->name('private-documents.view');
    Route::get('private-documents/{private_document}/download', [PrivateDocumentController::class, 'download'])->name('private-documents.download');
});


// 👤 6. مسارات الملف الشخصي والشراء والإشعارات (متاحة لكل مستخدم مسجل دخوله)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // 🔔 مسارات مركز الإشعارات
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});


// 🛒 7. مسارات سلة المشتريات
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');


// 🔑 8. مسارات المصادقة الجاهزة من Laravel Breeze
require __DIR__ . '/auth.php';