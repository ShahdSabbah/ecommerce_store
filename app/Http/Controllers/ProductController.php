<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. عرض جدول المنتجات مع الكاش والـ Eager Loading
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $page = (int) $request->get('page', 1);

        $cacheKey = "products_web_lang_{$locale}_page_{$page}";

        $products = Cache::remember($cacheKey, 3600, function () {
            return Product::with('category')->latest()->paginate(10);
        });

        return view('products.index', compact('products'));
    }

    // 2. صفحة إضافة منتج (مع تخزين الفئات بالكاش)
    public function create()
    {
        $locale = app()->getLocale();
        $cacheKey = "categories_all_lang_{$locale}";

        $categories = Cache::remember($cacheKey, 3600, function () {
            return Category::all();
        });

        return view('products.create', compact('categories'));
    }

    // 3. حفظ المنتج الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // رفع الصورة في مجلد products داخل public storage
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // الـ ProductObserver سيتولى مسح الكاش فور الإنشاء
        Product::create($data);

        // تسجيل الحركة في سجل النشاط
        try {
            ActivityLog::create([
                'user_name' => auth()->user()->name ?? 'زائر',
                'action'    => 'إضافة منتج',
                'details'   => 'تم إضافة منتج جديد باسم: ' . $request->name,
            ]);
        } catch (\Exception $e) {}

        return redirect()->route('products.index')->with('success', __('messages.product_created'));
    }

    // 4. صفحة تعديل المنتج
    public function edit(Product $product)
    {
        $locale = app()->getLocale();
        $cacheKey = "categories_all_lang_{$locale}";

        $categories = Cache::remember($cacheKey, 3600, function () {
            return Category::all();
        });

        return view('products.edit', compact('product', 'categories'));
    }

    // 5. تحديث بيانات المنتج
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // حذف الصورة القديمة واستبدالها بالجديدة
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // الـ ProductObserver سيتولى مسح الكاش فور التعديل
        $product->update($data);

        try {
            ActivityLog::create([
                'user_name' => auth()->user()->name ?? 'زائر',
                'action'    => 'تعديل منتج',
                'details'   => 'تم تعديل المنتج: ' . $product->name,
            ]);
        } catch (\Exception $e) {}

        return redirect()->route('products.index')->with('success', __('messages.product_updated'));
    }

    // 6. حذف المنتج
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // الـ ProductObserver سيتولى مسح الكاش فور الحذف
        $product->delete();

        return redirect()->route('products.index')->with('success', __('messages.product_deleted'));
    }
}