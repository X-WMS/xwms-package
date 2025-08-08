<?php

namespace XWMS\Package\Providers;

use Illuminate\Support\ServiceProvider;

class XwmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge default config met bestaande config.
        $this->mergeConfigFrom(__DIR__.'/../config/xwms.php', 'xwms');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/xwms.php' => config_path('xwms.php'),
        ], 'xwms-config');
    }
}
