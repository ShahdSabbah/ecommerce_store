<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart; 
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     *  عرض محتويات سلة المستخدم الحالي
     */
    public function index(Request $request)
    {
        $cartItems = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب محتويات السلة بنجاح',
            'data' => $cartItems
        ], 200);
    }

    /**
     *  إضافة منتج للسلة (أو زيادة كميته)
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
            $cartItem->increment('quantity', $quantity);
        } else {
            $cartItem = Cart::create([
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
     *  حذف عنصر من السلة
     */
    public function destroy(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'العنصر غير موجود في السلة'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المنتج من السلة بنجاح'
        ], 200);
    }
}
