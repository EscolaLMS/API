<?php

namespace App\Providers;

use App\Services\Contracts\EventServiceContract;
use App\Services\EventService;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        EventServiceContract::class => EventService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (strpos(config('app.url'), 'https') !== false) {
            \URL::forceScheme('https');
        }
        if (DB::Connection() instanceof SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction('REGEXP', function ($pattern, $value) {
                mb_regex_encoding('UTF-8');
                return (false !== mb_ereg($pattern, $value)) ? 1 : 0;
            });
        }
    }
}
