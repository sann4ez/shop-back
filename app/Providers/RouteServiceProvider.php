<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/started';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function startedRoute(Request $request)
    {
        /** @var User $user */
        if ($user = $request->user()) {
            if ($user->can('order.read')) {
                return redirect()->to('/admin/orders');
            } elseif ($user->can('dashboard.auth')) {
                return redirect()->to('/admin/hello');
            } elseif ($user->roles->count()) {
                return redirect()->to($request->_destination  ?: '/my');
            }
        }

        return redirect()->to('/');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            if ($by = $this->getRateLimiterBy($request)) {
                return Limit::perMinute(120)->by($by);
            }

            return Limit::none();
        });

        RateLimiter::for('auth-attempts', function (Request $request) {
            if ($by = $this->getRateLimiterBy($request)) {
                return Limit::perMinute(10)->by($by);
            }

            return Limit::none();
        });
    }

    protected function getRateLimiterBy(Request $request): bool|string
    {
        if ($userId = $request->user()?->id) {
            return $userId;
        }

        // IP клієнта
        $ip = $request->ip();

        // Дозволені безлімітні IP: фронтовського сервер (зазвичай той самий, що і бек),...
        $alloweds = config('auth.ips.x-reals', []);

        // IP сервера бекенду (поточний сервер)
        $hostname = gethostname();
        $serverIp = gethostbyname($hostname);
        $alloweds[] = $serverIp;

        // X-Real-IP Header
        $xRealIp = $request->header('X-Real-IP');

        if (in_array($ip, $alloweds) || $xRealIp && in_array($xRealIp, $alloweds)) {
            //$ip = $request->header('X-Real-IP');
            return false;
        }

        return $ip;
    }
}
