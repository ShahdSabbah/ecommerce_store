<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/cart",
     *      operationId="getCart",
     *      tags={"Cart"},
     *      summary="عرض محتويات سلة المستخدم الحالي",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تم جلب محتويات السلة بنجاح"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="غير مصرح به (Token مفقود أو غير صالح)"
     *      )
     * )
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $cacheKey = "user_cart_{$userId}";

        $cartData = Cache::remember($cacheKey, 600, function () use ($userId) {
            $items = Cart::with('product')
                ->where('user_id', $userId)
                ->get();

            $totalPrice = $items->sum(function ($item) {
                return $item->product ? ($item->product->price * $item->quantity) : 0;
            });

            $itemsCount = $items->sum('quantity');

            return [
                'items' => $items,
                'items_count' => $itemsCount,
                'total_price' => round($totalPrice, 2)
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'تم جلب محتويات السلة بنجاح',
            'data' => $cartData
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
     *              required={"product_id"},
     *              @OA\Property(property="product_id", type="integer", example=1),
     *              @OA\Property(property="quantity", type="integer", default=1, example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تمت إضافة المنتج إلى السلة بنجاح"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="الكمية المطلوبة تتجاوز المخزون أو المنتج غير متوفر"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="غير مصرح به"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $productId = (int) $request->product_id;
        $quantity = (int) $request->input('quantity', 1);
        $userId = $request->user()->id;

        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'عذراً، هذا المنتج غير متوفر في المخزون حالياً'
            ], 422);
        }

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        $currentQty = $cartItem ? $cartItem->quantity : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return response()->json([
                'status' => false,
                'message' => "عذراً، الكمية المطلوبة تتجاوز المخزون المتوفر (المتوفر: {$product->stock})"
            ], 422);
        }

        if ($cartItem) {
            $cartItem->quantity = $newQty;
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        // تفريغ الكاش الخاص بسلة هذا المستخدم
        Cache::forget("user_cart_{$userId}");

        return response()->json([
            'status' => true,
            'message' => 'تمت إضافة المنتج إلى السلة بنجاح',
            'data' => $cartItem->load('product')
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/cart/{id}",
     *      operationId="updateCartItem",
     *      tags={"Cart"},
     *      summary="تحديث كمية منتج في السلة",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="معرف عنصر السلة (Cart Item ID)",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\JsonContent(
     *              @OA\Property(property="quantity", type="integer", example=2, description="الكمية الجديدة مباشرة"),
     *              @OA\Property(property="type", type="string", enum={"increase","decrease"}, example="increase", description="أو نوع العملية: زيادة أو نقصان")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تم تحديث كمية المنتج بنجاح"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="العنصر غير موجود في السلة"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="الكمية المطلوبة تتجاوز المخزون المتوفر"
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $userId = $request->user()->id;

        $cartItem = Cart::with('product')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'العنصر غير موجود في السلة'
            ], 404);
        }

        $product = $cartItem->product;
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج المرتبط بهذا العنصر لم يعد متوفراً'
            ], 404);
        }

        if ($request->has('quantity')) {
            $newQuantity = (int) $request->input('quantity');
        } elseif ($request->input('type') === 'increase') {
            $newQuantity = $cartItem->quantity + 1;
        } elseif ($request->input('type') === 'decrease') {
            $newQuantity = $cartItem->quantity - 1;
        } else {
            return response()->json([
                'status' => false,
                'message' => 'يرجى تحديد quantity أو type (increase/decrease)'
            ], 422);
        }

        // إذا أصبحت الكمية 0 أو أقل، نحذف العنصر
        if ($newQuantity <= 0) {
            $cartItem->delete();
            Cache::forget("user_cart_{$userId}");
            return response()->json([
                'status' => true,
                'message' => 'تم حذف المنتج من السلة لأن الكمية أصبحت صفر'
            ], 200);
        }

        // التحقق من المخزون
        if ($newQuantity > $product->stock) {
            return response()->json([
                'status' => false,
                'message' => "عذراً، أقصى كمية متوفرة في المخزون هي: {$product->stock}"
            ], 422);
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        Cache::forget("user_cart_{$userId}");

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث الكمية بنجاح',
            'data' => $cartItem
        ], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/cart/{id}",
     *      operationId="removeCartItem",
     *      tags={"Cart"},
     *      summary="حذف منتج معين من السلة",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="معرف عنصر السلة (Cart Item ID)",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تم حذف المنتج من السلة بنجاح"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="العنصر غير موجود في السلة"
     *      )
     * )
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

        $cartItem->delete();
        Cache::forget("user_cart_{$userId}");

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المنتج من السلة بنجاح'
        ], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/cart",
     *      operationId="clearCart",
     *      tags={"Cart"},
     *      summary="تفريغ السلة بالكامل للمستخدم الحالي",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="تم تفريغ السلة بنجاح"
     *      )
     * )
     */
    public function clear(Request $request)
    {
        $userId = $request->user()->id;

        Cart::where('user_id', $userId)->delete();
        Cache::forget("user_cart_{$userId}");

        return response()->json([
            'status' => true,
            'message' => 'تم تفريغ السلة بنجاح'
        ], 200);
    }
}
