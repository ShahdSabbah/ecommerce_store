<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getProductsList",
     *      tags={"Products"},
     *      summary="عرض جميع المنتجات",
     *      description="يرجع قائمة بكل المنتجات المتاحة في المتجر",
     *      @OA\Parameter(
     *          name="Accept-Language",
     *          in="header",
     *          required=false,
     *          description="لغة الاستجابة (ar أو en)",
     *          @OA\Schema(type="string", default="ar")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تمت العملية بنجاح"
     *      )
     * )
     */
    public function index(Request $request)
{
    $locale = $request->header('Accept-Language', 'ar');
    app()->setLocale($locale);

    $perPage = $request->get('per_page', 10);
    $page = $request->get('page', 1);

    $cacheKey = "products_lang_{$locale}_page_{$page}_per_{$perPage}";

    $products = Cache::remember($cacheKey, 3600, function () use ($perPage, $locale) {
        $p = Product::latest()->paginate($perPage);

        $p->getCollection()->transform(function ($product) use ($locale) {
            $translationsName = $product->getTranslations('name');
            $name = $product->getTranslation('name', $locale, false);
            if (empty($name)) {
                $name = $product->getTranslation('name', 'en', false) ?? (is_array($translationsName) ? reset($translationsName) : '');
            }

            $translationsDesc = $product->getTranslations('description');
            $description = $product->getTranslation('description', $locale, false);
            if (empty($description)) {
                $description = $product->getTranslation('description', 'en', false) ?? (is_array($translationsDesc) ? reset($translationsDesc) : '');
            }

            return [
                'id'          => $product->id,
                'category_id' => $product->category_id,
                'name'        => $name ?: '',
                'description' => $description ?: '',
                'price'       => $product->price,
                'stock'       => $product->stock,
                'image'       => $product->image,
                'created_at'  => $product->created_at,
                'updated_at'  => $product->updated_at,
            ];
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
     *      tags={"Products"},
     *      summary="عرض تفاصيل منتج واحد",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer", example=61)
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
    public function show($id)
    {
        $locale = request()->header('Accept-Language', 'ar');
        app()->setLocale($locale);

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        $formattedProduct = [
            'id'          => $product->id,
            'category_id' => $product->category_id,
            'name'        => $product->name,
            'description' => $product->description,
            'price'       => $product->price,
            'stock'       => $product->stock,
            'image'       => $product->image,
            'created_at'  => $product->created_at,
            'updated_at'  => $product->updated_at,
        ];

        return response()->json([
            'status'  => true,
            'message' => 'تم جلب تفاصيل المنتج بنجاح',
            'data'    => $formattedProduct
        ], 200);
    }
}