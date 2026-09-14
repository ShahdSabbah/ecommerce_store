<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * عرض جميع الأقسام
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الأقسام بنجاح',
            'data' => $categories
        ], 200);
    }

    /**
     * عرض قسم معين مع المنتجات التابعة له
     */
    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'القسم غير موجود'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب بيانات القسم مع منتجاته بنجاح',
            'data' => $category
        ], 200);
    }
}
