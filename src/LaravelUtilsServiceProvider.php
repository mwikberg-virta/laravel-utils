<?php declare(strict_types=1);

namespace MLL\LaravelUtils;

use Illuminate\Support\ServiceProvider;

class LaravelUtilsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/stubs' => $this->app->basePath('stubs'),
        ], ['strict-stubs']);
    }
}
