<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\Course;
use App\Models\StationaryEvent;
use App\Models\Webinar;
use EscolaLms\Cart\Facades\Shop;
use EscolaLms\Cart\Services\Contracts\ProductServiceContract;
use EscolaLms\Consultations\Http\Resources\ConsultationSimpleResource;
use EscolaLms\Courses\Http\Resources\CourseListResource;
use EscolaLms\Courses\Http\Resources\CourseSimpleResource;
use EscolaLms\StationaryEvents\Http\Resources\StationaryEventResource;
use EscolaLms\Webinar\Http\Resources\WebinarSimpleResource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    private ProductServiceContract $productServiceContract;

    public function register()
    {
        $this->productServiceContract = app(ProductServiceContract::class);

        Shop::registerProductableClass(Consultation::class);
        ConsultationSimpleResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Consultation::class,
                $element->getKey()
            )
        );
        try {
            if ($element->hasYT()) {
                Shop::registerProductableClass(Webinar::class);
                WebinarSimpleResource::extend(
                    fn ($element) =>
                    $this->registerProductToResource(
                        Webinar::class,
                        $element->getKey()
                    )
                );
            }
        } catch (\Exception $exception) {
            //
        }

        Shop::registerProductableClass(Course::class);
        CourseSimpleResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Course::class,
                $element->getKey()
            )
        );
        CourseListResource::extend(
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

    private function registerProductToResource(string $class, int $id): array
    {
        if (!isset($this->productServiceContract)) {
            throw new BindingResolutionException();
        }
        $product = $this->productServiceContract->findProductable(
            $class,
            $id
        );
        return ['product' => $this->productServiceContract->findSingleProductForProductable($product)];
    }
}
