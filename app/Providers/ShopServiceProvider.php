<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\Course;
use App\Models\StationaryEvent;
use App\Models\Webinar;
use EscolaLms\Cart\EscolaLmsCartServiceProvider;
use EscolaLms\Cart\Facades\Shop;
use EscolaLms\Cart\Services\Contracts\ProductServiceContract;
use EscolaLms\Cart\Services\ProductService;
use EscolaLms\Consultations\Http\Resources\ConsultationSimpleResource;
use EscolaLms\Courses\Http\Resources\CourseSimpleResource;
use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\Webinar\Http\Resources\WebinarSimpleResource;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->getProviders(EscolaLmsCartServiceProvider::class)) {
            $this->app->register(EscolaLmsCartServiceProvider::class);
        }
    }

    public function boot()
    {
        Shop::registerProductableClass(Consultation::class);
        ConsultationSimpleResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Consultation::class,
                $element->getKey()
            )
        );
        Shop::registerProductableClass(Webinar::class);
        WebinarSimpleResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Webinar::class,
                $element->getKey()
            )
        );
        Shop::registerProductableClass(Course::class);
        CourseSimpleResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Course::class,
                $element->getKey()
            )
        );
        Shop::registerProductableClass(StationaryEvent::class);
        StationaryEventResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                StationaryEvent::class,
                $element->getKey()
            )
        );
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
