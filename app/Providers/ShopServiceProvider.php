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
use Illuminate\Database\Eloquent\Model;
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

        ConsultationSimpleResource::extend(function (Model $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(Consultation::class, $element->getKey()))];
        });

        Shop::registerProductableClass(Webinar::class);
        WebinarSimpleResource::extend(function (Model $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(Webinar::class, $element->getKey()))];
        });

        Shop::registerProductableClass(Course::class);
        CourseSimpleResource::extend(function (Model $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(Course::class, $element->getKey()))];
        });

        Shop::registerProductableClass(StationaryEvent::class);
        StationaryEventResource::extend(function (Model $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(StationaryEvent::class, $element->getKey()))];
        });
    }
}
