<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);

        if (in_array($locale, config('locale.available_locales', ['ar', 'en']))) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        } else {
            $locale = session('locale', config('locale.default', 'ar'));
            app()->setLocale($locale);
        }

        // يمرر لغة النظام افتراضياً لكافة الـ routes التي تطلب {locale}
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}