<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 1️⃣ تسجيل حساب جديد (Register)
     */
    public function register(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // التأكد من أن الإيميل غير مستخدم سابقاً
            'password' => 'required|string|min:8|confirmed',        // يتطلب وجود حقل password_confirmation في الطلب
        ]);

        // إنشاء المستخدم في قاعدة البيانات وتشفير كلمة المرور
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // توليد الـ Token مباشرة ليدخل المستخدم للتطبيق فوراً بعد التسجيل
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'تم إنشاء الحساب بنجاح',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * 2️⃣ تسجيل الدخول (Login)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'بيانات الدخول غير صحيحة'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * 3️⃣ تسجيل الخروج (Logout)
     */
    public function logout(Request $request)
    {
        // إلغاء وحذف الـ Token الحالي الذي استخدمه المستخدم لإرسال الطلب
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم تسجيل الخروج وإلغاء الـ Token بنجاح'
        ], 200);
    }
}
