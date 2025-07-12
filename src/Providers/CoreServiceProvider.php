<?php

namespace LaravelShared\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Schema\Builder;

class CoreServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'core');

        // Vertalingen
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'core');

        // Config publishen
        $this->publishes([
            __DIR__.'/../config' => config_path('core'),
        ], 'core-config');

        $this->publishes([
            __DIR__.'/../resources/assets/css' => resource_path('core/css'),
            __DIR__.'/../resources/assets/scss' => resource_path('core/scss'),
            __DIR__.'/../resources/assets/js' => resource_path('core/js'),
            __DIR__.'/../resources/assets/packages' => resource_path('core/packages'),
            __DIR__.'/../resources/lang' => resource_path('core/lang'),
            __DIR__.'/../resources/views' => resource_path('views/core'),
        ], 'core-resources');

        $this->publishes([
            __DIR__.'/../routes' => base_path('routes/core'),
        ], 'core-routes');

        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->environment('local')) {
            Artisan::call('vendor:publish', [
                '--provider' => "LaravelShared\\Core\\Providers\\CoreServiceProvider",
                '--force' => true
            ]);

            $this->cleanupStaleFiles([
                [
                    'from' => __DIR__.'/../resources/assets/css',
                    'to'   => resource_path('core/css'),
                ],
                [
                    'from' => __DIR__.'/../resources/assets/scss',
                    'to'   => resource_path('core/scss'),
                ],
                [
                    'from' => __DIR__.'/../resources/assets/js',
                    'to'   => resource_path('core/js'),
                ],
                [
                    'from' => __DIR__.'/../resources/assets/packages',
                    'to'   => resource_path('core/packages'),
                ],
                [
                    'from' => __DIR__.'/../resources/lang',
                    'to'   => resource_path('core/lang'),
                ],
                [
                    'from' => __DIR__.'/../resources/views',
                    'to'   => resource_path('views/core'),
                ],
                [
                    'from' => __DIR__.'/../routes',
                    'to'   => base_path('routes/core'),
                ],
            ]);

            if (!File::exists(public_path('build/manifest.json'))) {
                exec('npm run build');
            }
        }        
    }

    protected function cleanupStaleFiles(array $paths)
    {
        foreach ($paths as $pair) {
            [$from, $to] = [$pair['from'], $pair['to']];
            
            if (!File::exists($to)) continue;

            $publishedFiles = collect(File::allFiles($to))->map(function ($file) use ($to) {
                return str_replace('\\', '/', str_replace($to.'/', '', $file->getPathname()));
            });

            $originalFiles = collect(File::allFiles($from))->map(function ($file) use ($from) {
                return str_replace('\\', '/', str_replace($from.'/', '', $file->getPathname()));
            });

            $staleFiles = $publishedFiles->diff($originalFiles);

            foreach ($staleFiles as $staleFile) {
                $fullPath = $to . '/' . $staleFile;
                File::delete($fullPath);
                // echo "🧹 Verwijderd: $fullPath\n";
            }
        }
    }
}
