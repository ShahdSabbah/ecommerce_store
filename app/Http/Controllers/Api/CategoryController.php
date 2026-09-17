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

        $cacheKey = "categories_lang_{$locale}";

        $categories = Cache::remember($cacheKey, 86400, function () use ($locale) {
            $cats = Category::all();

            return $cats->map(function ($category) use ($locale) {
                $translations = $category->getTranslations('name');
                $name = $category->getTranslation('name', $locale, false);

                if (empty($name)) {
                    $fallback = $category->getTranslation('name', 'en', false);
                    $name = !empty($fallback) ? $fallback : (is_array($translations) ? reset($translations) : '');
                }

                return [
                    'id'   => $category->id,
                    'name' => $name ?: '',
                ];
            });
        });

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الأقسام بنجاح',
            'data' => $categories
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

        $cacheKey = "category_{$id}_lang_{$locale}";

        $category = Cache::remember($cacheKey, 86400, function () use ($id, $locale) {
            $cat = Category::with('products')->find($id);
            if (!$cat) return null;

            $translations = $cat->getTranslations('name');
            $name = $cat->getTranslation('name', $locale, false);

            if (empty($name)) {
                $fallback = $cat->getTranslation('name', 'en', false);
                $name = !empty($fallback) ? $fallback : (is_array($translations) ? reset($translations) : '');
            }

            return [
                'id'       => $cat->id,
                'name'     => $name ?: '',
                'products' => $cat->products
            ];
        });

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