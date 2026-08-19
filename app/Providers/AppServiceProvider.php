<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\View\DynamicComponent;
use App\Helpers\PermissionHelper;
use App\Compilers\ResilientBladeCompiler;
use App\Broadcasting\SafeBroadcaster;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return tap(new ResilientBladeCompiler(
                $app['files'],
                $app['config']['view.compiled'],
                $app['config']->get('view.relative_hash', false) ? $app->basePath() : '',
                $app['config']->get('view.cache', true),
                $app['config']->get('view.compiled_extension', 'php'),
                $app['config']->get('view.check_cache_timestamps', true),
            ), function ($blade) {
                $blade->component('dynamic-component', DynamicComponent::class);
            });
        });
    }

    public function boot(): void
    {
        Blade::if('canAccess', function ($menu, $action) {
            return PermissionHelper::canAccess($menu, $action);
        });

        Broadcast::extend('reverb', function ($app, $config) {
            $manager = $app->make(BroadcastManager::class);

            return new SafeBroadcaster($manager->pusher($config), $config['jsonp'] ?? false);
        });
    }
}
