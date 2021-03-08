<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\ImageServiceContract;
use App\Services\Contracts\ProgressServiceContract;
use App\Services\Contracts\StatsServiceContract;
use App\Services\Contracts\UserServiceContract;
use App\Services\Contracts\VideoServiceContract;
use App\Services\ImageService;
use App\Services\ProgressService;
use App\Services\StatsService;
use App\Services\EscolaLMS\BlogService;
use App\Services\EscolaLMS\CategoryService;
use App\Services\EscolaLMS\ConfigService;
use App\Services\EscolaLMS\Contracts\BlogServiceContract;
use App\Services\EscolaLMS\Contracts\CategoryServiceContracts;
use App\Services\EscolaLMS\Contracts\ConfigServiceContract;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use App\Services\EscolaLMS\Contracts\H5PContentServiceContract;
use App\Services\EscolaLMS\Contracts\InstructorServiceContract;
use App\Services\EscolaLMS\CourseService;
use App\Services\EscolaLMS\H5PContentService;
use App\Services\EscolaLMS\InstructorService;
use App\Services\UserService;
use App\Services\VideoService;
use EscolaLms\Core\Providers\Injectable;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use Injectable;

    const CONTRACTS = [
        AuthServiceContract::class => AuthService::class,
        BlogServiceContract::class => BlogService::class,
        CourseServiceContract::class => CourseService::class,
        CategoryServiceContracts::class => CategoryService::class,
        InstructorServiceContract::class => InstructorService::class,
        VideoServiceContract::class => VideoService::class,
        UserServiceContract::class => UserService::class,
        ConfigServiceContract::class => ConfigService::class,
        H5PContentServiceContract::class => H5PContentService::class,
        ImageServiceContract::class => ImageService::class,
        ProgressServiceContract::class => ProgressService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->injectContract(self::CONTRACTS);
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') != 'local' || strpos(config('app.url'), 'https') !== false) {
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
