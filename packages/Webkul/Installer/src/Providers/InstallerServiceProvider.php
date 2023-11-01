<?php

namespace Webkul\Installer\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Installer\Console\Commands\Installer as InstallerCommand;
use Webkul\Installer\Http\Middleware\CanInstall;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     * 
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('install', [CanInstall::class]);

        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'installer');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'installer');

        Event::listen('bagisto.installed', 'Webkul\Installer\Listeners\Installer@installed');
    }

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register the Installer Commands of this package.
     * 
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallerCommand::class,
            ]);
        }
    }
}
