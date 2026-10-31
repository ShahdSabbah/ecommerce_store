<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    /**
     * عرض محتويات سلة المستخدم الحالي
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $cacheKey = "user_cart_{$userId}";

        // جلب البيانات من الكاش أو الاستعلام من DB وتخزينها
        $cartItems = Cache::remember($cacheKey, 600, function () use ($userId) {
            return Cart::with('product')
                ->where('user_id', $userId)
                ->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'تم جلب محتويات السلة بنجاح',
            'data' => $cartItems
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/cart",
     *      operationId="addToCart",
     *      tags={"Cart"},
     *      summary="إضافة منتج للسلة",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"product_id","quantity"},
     *              @OA\Property(property="product_id", type="integer", example=2),
     *              @OA\Property(property="quantity", type="integer", example=2)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تمت إضافة المنتج بنجاح"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);
        $userId = $request->user()->id;

        // التحقق مما إذا كان المنتج موجوداً مسبقاً في سلة المستخدم
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity); // الـ Observer سينصت لحدث التحديث
        } else {
            $cartItem = Cart::create([                   // الـ Observer سينصت لحدث الإنشاء
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'تمت إضافة المنتج إلى السلة بنجاح',
            'data' => $cartItem
        ], 200);
    }

    /**
     * حذف عنصر من السلة
     */
    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;

        $cartItem = Cart::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'العنصر غير موجود في السلة'
            ], 404);
        }

        $cartItem->delete(); // الـ Observer ينصت لحدث الحذف ويمسح الكاش تلقائياً

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المنتج من السلة بنجاح'
        ], 200);
    }
}