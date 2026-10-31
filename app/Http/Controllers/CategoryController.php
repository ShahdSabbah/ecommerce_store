<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $page = (int) $request->get('page', 1);

        // بناء مفتاح كاش ديناميكي يتوافق مع اللغة ورقم الصفحة
        $cacheKey = "categories_web_lang_{$locale}_page_{$page}";

        // تخزين الاستعلام مؤقتاً لمدة ساعة (3600 ثانية)
        $categories = Cache::remember($cacheKey, 3600, function () {
            return Category::latest()->paginate(10);
        });

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // الـ CategoryObserver سيمسح الكاش تلقائياً عند الإضافة
        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', __('messages.category_created'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // الـ CategoryObserver سيمسح الكاش تلقائياً عند التعديل
        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category)
    {
        // الـ CategoryObserver سيمسح الكاش تلقائياً عند الحذف
        $category->delete();

        return redirect()->route('categories.index')->with('success', __('messages.category_deleted'));
    }
}