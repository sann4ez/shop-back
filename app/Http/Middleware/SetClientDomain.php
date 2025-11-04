<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Scopes\AllowedScope;

class SetClientDomain
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->get('locale')
            ?? $request->header('X-Locale')
            ?? $request->cookie('locale')
            ?? config('app.locale', 'uk');

        app()->setLocale($locale);

//        \App\Models\Page::addGlobalScope(new AllowedScope);
//        \App\Models\Post::addGlobalScope(new AllowedScope);
        \App\Models\Block::addGlobalScope(new AllowedScope);
//        \App\Models\Term::addGlobalScope(new AllowedScope);
//        \App\Models\Shop\Product::addGlobalScope(new AllowedScope);
//        \App\Models\Shop\ProductVariation::addGlobalScope(new AllowedScope);

        // Якщо запит йде з API — ставимо опцію для Block
        if ($request->is('api/*')) {
            \Block::setOptions(['response' => 'resource']);
        }

        return $next($request);
    }
}
