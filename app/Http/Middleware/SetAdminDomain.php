<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Block;

class SetAdminDomain
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Визначаємо локаль (із запиту або конфігурації)
        $locale = $request->get('locale')
            ?? $request->header('X-Locale')
            ?? config('app.locale', 'uk');

        app()->setLocale($locale);
//        config()->set('translatable.locale', $locale);
//
//        config()->set('translatable.fallback_locale', config('app.fallback_locale', 'en'));

        \Variable::setGroup('default');

        return $next($request);
    }
}
