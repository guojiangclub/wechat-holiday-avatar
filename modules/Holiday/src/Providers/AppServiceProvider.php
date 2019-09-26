<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\HolidayAvatar\Server\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;

class AppServiceProvider extends ServiceProvider
{
    protected $namespace = 'iBrand\HolidayAvatar\Server\Http\Controllers';

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        \Log::info('aaaaa');
        parent::boot();
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
        }
    }

    public function register()
    {

    }

    public function map()
    {\Log::info('bbbbbbb');
        $this->mapWebRoute();

        Route::prefix('api')
            ->middleware(['api', 'cors'])
            ->namespace($this->namespace)
            ->group(__DIR__.'/../Http/routes.php');
    }

    protected function mapWebRoute()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            $router->get('oauth/wxlogin', 'AuthController@wxlogin')->name('api.oauth.wxlogin');
            $router->get('oauth/getUerInfo', 'AuthController@getUerInfo')->name('api.oauth.getUerInfo');
        });
    }


}
