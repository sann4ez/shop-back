<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($url = env('NGROK_URL')) {
            URL::forceScheme(env('NGROK_SCHEME') ?: 'https');
            URL::forceRootUrl($url);
        }

        $this->setMorphMap();

        Paginator::useBootstrap();
    }

    protected function setMorphMap()
    {
        Relation::morphMap([
            'user' => \App\Models\User::class,

            'visit' => \App\Models\Visit::class,

            'item' => \App\Models\Item::class,

            'menuitem' => \App\Models\Menuitem::class,
            'mediatemporary' => \Fomvasss\MediaLibraryExtension\Models\MediaTemporary::class,

            'domain' => \App\Models\Domain::class,
            'seo' => \App\Models\Seo::class,
            'term' => \App\Models\Term::class,

            'page' => \App\Models\Page::class,
            'block' => \App\Models\Block::class,
            'post' => \App\Models\Post::class,
            'lead' => \App\Models\Lead::class,
        ]);
    }
}
