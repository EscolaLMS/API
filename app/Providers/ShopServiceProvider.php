<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\Course;
use App\Models\StationaryEvent;
use App\Models\Webinar;
use EscolaLms\Cart\EscolaLmsCartServiceProvider;
use EscolaLms\Cart\Facades\Shop;
use EscolaLms\Consultations\Http\Resources\ConsultationSimpleResource;
use EscolaLms\Courses\Http\Resources\CourseSimpleResource;
use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\Webinar\Http\Resources\WebinarSimpleResource;
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
        ConsultationSimpleResource::extend(function (ConsultationSimpleResource $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(Consultation::class, $element->id))];
        });

        Shop::registerProductableClass(Webinar::class);
        WebinarSimpleResource::extend(function (WebinarSimpleResource $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(Webinar::class, $element->id))];
        });

        Shop::registerProductableClass(Course::class);
        CourseSimpleResource::extend(function (CourseSimpleResource $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(Course::class, $element->id))];
        });

        Shop::registerProductableClass(StationaryEvent::class);
        StationaryEventResource::extend(function (StationaryEventResource $element) {
            return ['product' => Shop::findSingleProductForProductable(Shop::findProductable(StationaryEvent::class, $element->id))];
        });
    }
}
