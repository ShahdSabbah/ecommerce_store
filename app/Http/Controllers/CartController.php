<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    // عرض صفحة السلة من الـ Session مباشرة
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // إضافة منتج للسلة
    public function add(Request $request, $id)
    {
        $locale = app()->getLocale();

        // استخدام Cache لجلب تفاصيل المنتج وتخفيف الضغط على قاعدة البيانات
        $product = Cache::remember("product_detail_{$id}_lang_{$locale}", 3600, function () use ($id) {
            return Product::find($id);
        });

        if (!$product) {
            return redirect()->back()->with('error', __('messages.product_not_found'));
        }

        // الحماية من إضافة منتج نفد من المخزون
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', __('messages.out_of_stock'));
        }

        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        // إذا كان المنتج موجوداً مسبقاً في السلة نزيد الكمية
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            // إضافة منتج جديد مع الاسم المترجم
            $cart[$id] = [
                "name"     => $product->name,
                "quantity" => $quantity,
                "price"    => $product->price,
                "image"    => $product->image_url
            ];
        }

        session()->put('cart', $cart);
        session()->save();

        return redirect()->back()->with('success', __('messages.product_added_to_cart'));
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

        return redirect()->back()->with('success', __('messages.item_removed_from_cart'));
    }

    // تحديث كمية منتج في السلة
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $type = $request->input('type');

            if ($type === 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($type === 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            }

            session()->put('cart', $cart);
            session()->save();
        }

        return redirect()->back()->with('success', __('messages.cart_updated'));
    }
}