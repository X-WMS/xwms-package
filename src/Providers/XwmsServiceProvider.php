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
        ], 'config');

        $this->syncConfigKeys();
    }

    protected function syncConfigKeys()
    {
        $defaultConfig = require __DIR__.'/../config/xwms.php';
        $userConfigPath = config_path('xwms.php');

        if (!file_exists($userConfigPath)) {
            return; // niks te doen als user config niet bestaat
        }

        $userConfig = require $userConfigPath;

        $mergedConfig = array_replace_recursive($defaultConfig, $userConfig);

        // Voeg keys uit defaultConfig toe die ontbreken in userConfig
        foreach ($defaultConfig as $key => $value) {
            if (!array_key_exists($key, $userConfig)) {
                $userConfig[$key] = $value;
            }
        }

        // Schrijf terug naar config/xwms.php
        $configContent = "<?php\n\nreturn " . var_export($userConfig, true) . ";\n";
        file_put_contents($userConfigPath, $configContent);
    }
}
