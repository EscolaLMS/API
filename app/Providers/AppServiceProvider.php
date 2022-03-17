<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\Course;
use App\Models\StationaryEvent;
use App\Models\Webinar;
use App\Services\Contracts\RegisterProductServiceContract;
use App\Services\RegisterProductService;
use EscolaLms\Cart\Facades\Shop;
use EscolaLms\Cart\Services\Contracts\ProductServiceContract;
use EscolaLms\Consultations\Http\Resources\ConsultationSimpleResource;
use EscolaLms\Courses\Http\Resources\CourseSimpleResource;
use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\Webinar\Http\Resources\WebinarSimpleResource;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const SERVICES = [
        RegisterProductServiceContract::class => RegisterProductService::class
    ];
    public const REPOSITORIES = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (array_merge(self::SERVICES, self::REPOSITORIES) as $contract => $singleton) {
            $this->app->bind($contract, $singleton);
        }

        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        Shop::registerProductableClass(Consultation::class);
        ConsultationSimpleResource::extend(fn ($element) =>
            $this->registerProductToResource(
                Consultation::class,
                $element->getKey()
            )
        );
        Shop::registerProductableClass(Webinar::class);
        WebinarSimpleResource::extend(fn ($element) =>
            $this->registerProductToResource(
                Webinar::class,
                $element->getKey()
            )
        );
        Shop::registerProductableClass(Course::class);
        CourseSimpleResource::extend(fn ($element) =>
            $this->registerProductToResource(
                Course::class,
                $element->getKey()
            )
        );
        Shop::registerProductableClass(StationaryEvent::class);
        StationaryEventResource::extend(fn ($element) =>
            $this->registerProductToResource(
                StationaryEvent::class,
                $element->getKey()
            )
        );
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

    public function registerProductToResource(string $class, int $id): array
    {
        $productServiceContract = app(ProductServiceContract::class);
        $product = $productServiceContract->findProductable(
            $class,
            $id
        );
        return ['product' => $productServiceContract->findSingleProductForProductable($product)];
    }
}
