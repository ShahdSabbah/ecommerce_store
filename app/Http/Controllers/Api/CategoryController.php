<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/categories",
     *      tags={"Categories"},
     *      summary="عرض جميع الأقسام",
     *      @OA\Response(response=200, description="قائمة الأقسام")
     * )
     */
    public function index()
    {
        $locale = request()->header('Accept-Language', 'ar');
        app()->setLocale($locale);

        // مفتاح الكاش مع مراعاة اللغة
        $cacheKey = "categories_all_lang_{$locale}";

        // حفظ الأقسام في الكاش لمدة 24 ساعة (86400 ثانية) لأن الأقسام لا تتغير كثيراً
        $categories = Cache::remember($cacheKey, 86400, function () {
            $cats = Category::all();

            return $cats->map(function ($category) {
                return [
                    'id'          => $category->id,
                    'name'        => $category->name,
                    'description' => $category->description ?? '',
                ];
            });
        });

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب الأقسام بنجاح',
            'data'    => $categories
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
     *      summary="عرض تفاصيل قسم معين",
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *      @OA\Response(response=200, description="تفاصيل القسم")
     * )
     */
    public function show($id)
    {
        $locale = request()->header('Accept-Language', 'ar');
        app()->setLocale($locale);

        // مفتاح كاش فريد لكل قسم ولغة
        $cacheKey = "category_detail_{$id}_lang_{$locale}";

        $category = Cache::remember($cacheKey, 86400, function () use ($id) {
            $cat = Category::with('products')->find($id);

            if (!$cat) {
                return null;
            }

            return [
                'id'          => $cat->id,
                'name'        => $cat->name,
                'description' => $cat->description ?? '',
                'products'    => $cat->products
            ];
        });

        if (!$category) {
            return response()->json([
                'status'  => false,
                'message' => 'القسم غير موجود'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب بيانات القسم مع منتجاته بنجاح',
            'data'    => $category
        ], 200);
    }
}