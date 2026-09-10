<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // عرض صفحة السلة
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // إضافة منتج للسلة
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        // إذا كان المنتج موجوداً مسبقاً في السلة نزيد الكمية
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            // إضافة منتج جديد
            $cart[$id] = [
                "name"     => $product->name,
                "quantity" => $quantity,
                "price"    => $product->price,
                "image"    => $product->image
            ];
        }

        session()->put('cart', $cart);
        session()->save(); // حفظ مباشر للجلسة لتفادي الفقدان عند الـ Redirect

        return redirect()->back()->with('success', 'تمت إضافة المنتج للسلة بنجاح!');
    }

    // حذف منتج من السلة
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            session()->save();
        }

        return redirect()->back()->with('success', 'تم حذف المنتج من السلة!');
    }
    // تحديث كمية منتج في السلة
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $type = $request->input('type'); // 'increase' أو 'decrease'

            if ($type === 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($type === 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            }

            session()->put('cart', $cart);
            session()->save();
        }

        return redirect()->back();
    }
}
