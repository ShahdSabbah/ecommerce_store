<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. إذا لم يكن المستخدم مسجلاً، وجهه لصفحة تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. إذا كان المستخدم سوبر أدمن، اسمح له بالمرور
        if (auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        // 3. إذا كان مديراً عادياً أو عميلاً حاول الدخول، امنعه مع رسالة تنبيه
        return redirect()->route('dashboard')->with('error', 'عذراً، هذه الصفحة مخصصة للسوبر أدمن فقط (Super Admin).');
    }
}
