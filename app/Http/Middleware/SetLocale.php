<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. الأولوية للجلسة session، ثم الكوكي cookie، ثم الافتراضي
        $locale = $request->session()->get('locale') 
               ?? $request->cookie('locale') 
               ?? config('app.locale', 'ar');

        // 2. التأكد من أن اللغة مدعومة
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // 3. ضبط لغة التطبيق الحالية
        app()->setLocale($locale);

        // 4. مزامنة الجلسة إن لم تكن مضبوطة
        if ($request->session()->get('locale') !== $locale) {
            $request->session()->put('locale', $locale);
        }

        // 5. مشاركة متغيرات اللغة والاتجاه مع كافة قوالب Blade
        view()->share('current_locale', $locale);
        view()->share('dir', $locale === 'ar' ? 'rtl' : 'ltr');

        return $next($request);
    }
}