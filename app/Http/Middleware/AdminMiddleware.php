<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
{
    // 1. إذا مش مسجل دخوله أصلاً، وجهه لصفحة اللوجن
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // 2. إذا مسجل دخوله وهو أدمن أو سوبر أدمن، اتركه يمر للوحة التحكم
    if (auth()->user()->isAdmin()) {
        return $next($request);
    }

    // 3. إذا مسجل دخوله وهو (customer) وحاول يدخل لوحة التحكم، وجهه للصفحة الرئيسية (وليس login!)
    return redirect('/')->with('error', 'عذراً، هذه الصفحة مخصصة للمسؤولين فقط.');
}
}
