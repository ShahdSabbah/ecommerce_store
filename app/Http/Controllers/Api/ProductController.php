<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getProductsList",
     *      tags={"Products"},
     *      summary="عرض جميع المنتجات",
     *      description="يرجع قائمة بكل المنتجات المتاحة في المتجر مع التصفح والكاش",
     *      @OA\Parameter(
     *          name="Accept-Language",
     *          in="header",
     *          required=false,
     *          description="لغة الاستجابة (ar أو en)",
     *          @OA\Schema(type="string", default="ar")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="رقم الصفحة",
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          required=false,
     *          description="عدد العناصر في الصفحة",
     *          @OA\Schema(type="integer", default=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تمت العملية بنجاح"
     *      )
     * )
     */
    public function index(Request $request)
    {
        // 1. تحديد اللغة وتطبيقها
        $locale = $request->header('Accept-Language', config('app.locale', 'ar'));
        app()->setLocale($locale);

        // 2. معالجة قيم الـ Pagination
        $perPage = (int) $request->get('per_page', 10);
        $page = (int) $request->get('page', 1);

        // 3. مفتاح الكاش الفريد لكل لغة وصفحة
        $cacheKey = "products_lang_{$locale}_page_{$page}_per_{$perPage}";

        // 4. استرجاع أو تخزين البيانات
        $products = Cache::remember($cacheKey, 3600, function () use ($perPage, $locale) {
            $p = Product::latest()->paginate($perPage);

            // تحويل واستخراج الترجمات المناسبة للـ Collections
            $p->getCollection()->transform(function ($product) use ($locale) {
                return $this->formatProduct($product, $locale);
            });

            return $p;
        });

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب المنتجات بنجاح',
            'data'    => $products
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/products/{id}",
     *      operationId="getProductById",
     *      tags={"Products"},
     *      summary="عرض تفاصيل منتج واحد",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="Accept-Language",
     *          in="header",
     *          required=false,
     *          @OA\Schema(type="string", default="ar")
     *      ),
     *      @OA\Response(response=200, description="تمت العملية بنجاح"),
     *      @OA\Response(response=404, description="المنتج غير موجود")
     * )
     */
    public function show(Request $request, $id)
    {
        $locale = $request->header('Accept-Language', config('app.locale', 'ar'));
        app()->setLocale($locale);

        $cacheKey = "product_detail_{$id}_lang_{$locale}";

        $formattedProduct = Cache::remember($cacheKey, 3600, function () use ($id, $locale) {
            $product = Product::find($id);

            if (!$product) {
                return null;
            }

            return $this->formatProduct($product, $locale);
        });

        if (!$formattedProduct) {
            return response()->json([
                'status'  => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب تفاصيل المنتج بنجاح',
            'data'    => $formattedProduct
        ], 200);
    }

    /**
     * دالة مساعدة لتنسيق وتجهيز بيانات المنتج
     */
    private function formatProduct(Product $product, string $locale): array
    {
        return [
            'id'          => $product->id,
            'category_id' => $product->category_id,
            'name'        => $product->name,
            'description' => $product->description ?? '',
            'price'       => $product->price,
            'stock'       => $product->stock,
            'image_url'   => $product->image_url,
            'created_at'  => $product->created_at,
            'updated_at'  => $product->updated_at,
        ];
    }
}