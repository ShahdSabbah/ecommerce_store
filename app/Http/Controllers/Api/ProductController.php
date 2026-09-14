<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     *  عرض المنتجات مقسمة على صفحات (Pagination)
     */
    public function index(Request $request)
    {
        // نحدد عدد المنتجات في كل صفحة (مثلاً 10 منتجات)
        // ويمكن استلام العدد من العميل إذا أرسله مثل: /api/products?per_page=5
        $perPage = $request->get('per_page', 10);

        // بدل أخذ الكل بـ all()، نستخدم paginate()
        $products = Product::latest()->paginate($perPage);

        // إرجاع النتيجة بصيغة JSON
        return response()->json([
            'status' => true,
            'message' => 'تم جلب المنتجات بنجاح',
            'data' => $products
        ], 200);
    }

    /**
     *  عرض تفاصيل منتج واحد فقط برقمه (ID)
     */
    public function show($id)
    {
        $product = Product::find($id);

        // إذا لم يتم العثور على المنتج
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب تفاصيل المنتج بنجاح',
            'data' => $product
        ], 200);
    }
}
