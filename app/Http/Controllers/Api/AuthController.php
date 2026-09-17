<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Authentication"},
     *      summary="إنشاء حساب جديد",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="Ahmad Hassan"),
     *              @OA\Property(property="email", type="string", example="ahmad@example.com"),
     *              @OA\Property(property="password", type="string", example="password123"),
     *              @OA\Property(property="password_confirmation", type="string", example="password123")
     *          )
     *      ),
     *      @OA\Response(response=201, description="تم إنشاء الحساب بنجاح")
     * )
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
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Authentication"},
     *      summary="تسجيل الدخول والحصول على Token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="ahmad@example.com"),
     *              @OA\Property(property="password", type="string", example="password123")
     *          )
     *      ),
     *      @OA\Response(response=200, description="تم تسجيل الدخول بنجاح")
     * )
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
 * @OA\Post(
 *      path="/api/logout",
 *      tags={"Authentication"},
 *      summary="تسجيل الخروج",
 *      security={{"bearerAuth": {}}},
 *      @OA\Parameter(
 *          name="Accept",
 *          in="header",
 *          required=true,
 *          @OA\Schema(type="string", default="application/json")
 *      ),
 *      @OA\Response(response=200, description="تم تسجيل الخروج بنجاح"),
 *      @OA\Response(response=401, description="غير مصرح")
 * )
 */
public function logout(Request $request)
{
    $user = $request->user();

    if ($user && method_exists($user->currentAccessToken(), 'delete')) {
        $user->currentAccessToken()->delete();
    }

    return response()->json([
        'status'  => true,
        'message' => 'تم تسجيل الخروج بنجاح'
    ], 200);
}
}
