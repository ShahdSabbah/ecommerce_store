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

// 1. Redirect Root URL to stored or default locale (/ -> /ar)
Route::get('/', function () {
    $locale = session('locale', config('locale.default', 'ar'));
    return redirect()->to('/' . $locale);
})->name('locale.redirect');

// 2. Localized Routes Group (كل المسارات العامة التي تتطلب لغة)
Route::group([
    'prefix' => '{locale}',
    'where'  => ['locale' => 'ar|en'],
    'middleware' => ['web'],
], function () {

    // الصفحة الرئيسية للمتجر
    Route::get('/', function (Request $request, $locale) {
        $categories = Category::select('id', 'name')->get()->unique('name');
        $query = Product::with('category')->latest();
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products = $query->get();
        return view('welcome_store', compact('categories', 'products'));
    })->name('home');

    // مسارات المصادقة (Auth Routes: login, register, password, etc.)
    if (file_exists(__DIR__ . '/auth.php')) {
        require __DIR__ . '/auth.php';
    }
});

// 3. Global Static Routes (خارج نطاق البريفكس لتجنب تعارض 404)

// Dashboard (Admin Only)
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
    
    $locale = session('locale', config('locale.default', 'ar'));
    return redirect()->route('home', ['locale' => $locale]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Super admin routes
Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::resource('users', UserController::class);

    // استثناء show و edit و update من الـ resource لتجنب التضارب
    Route::resource('private-documents', PrivateDocumentController::class)->except(['show', 'edit', 'update']);

    // المسارات المخصصة للمعاينة والتحميل
    Route::get('private-documents/{private_document}/view', [PrivateDocumentController::class, 'show'])->name('private-documents.view');
    Route::get('private-documents/{private_document}/download', [PrivateDocumentController::class, 'download'])->name('private-documents.download');
});

// Profile & checkout routes (any authenticated user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');