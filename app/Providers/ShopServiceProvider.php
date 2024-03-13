<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\Course;
use App\Models\Dictionary;
use App\Models\StationaryEvent;
use App\Models\Webinar;
use EscolaLms\Cart\Facades\Shop;
use EscolaLms\Cart\Http\Resources\ProductResource;
use EscolaLms\Cart\Models\Product;
use EscolaLms\Cart\Services\Contracts\ProductServiceContract;
use EscolaLms\Consultations\Http\Resources\ConsultationSimpleResource;
use EscolaLms\Consultations\Http\Resources\ConsultationTermsResource;
use EscolaLms\Courses\Http\Resources\CourseListResource;
use EscolaLms\Courses\Http\Resources\CourseSimpleResource;
use EscolaLms\Dictionaries\Http\Resources\DictionaryResource;
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
                $element
            )
        );
        ConsultationTermsResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Consultation::class,
                $element
            )
        );
        Shop::registerProductableClass(Webinar::class);
        WebinarSimpleResource::extend(
            function($element) {
                try {
                    if ($element->hasYT()) {
                        return $this->registerProductToResource(
                            Webinar::class,
                            $element
                        );
                    }
                } catch (\Exception $exception) {
                    //
                }
                return [
                    'product' => null
                ];
            }
        );

        Shop::registerProductableClass(Course::class);
        CourseSimpleResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Course::class,
                $element
            )
        );
        CourseListResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Course::class,
                $element
            )
        );
        Shop::registerProductableClass(StationaryEvent::class);
        StationaryEventResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                StationaryEvent::class,
                $element
            )
        );
        Shop::registerProductableClass(Dictionary::class);
        DictionaryResource::extend(
            fn ($element) =>
            $this->registerProductToResource(
                Dictionary::class,
                $element
            )
        );
    }

    private function registerProductToResource(string $class, $element): array
    {
        if (!isset($this->productServiceContract)) {
            throw new BindingResolutionException();
        }
        $product = $this->productServiceContract->findProductable(
            $class,
            $element->getKey()
        );
        $productId = $element->product_id ?? null;
        $relatedProduct = null;
        $prod = null;
        if ($productId) {
            $relatedProduct = Product::whereId($productId)->first();
        }
        if ($product) {
            $prod = $this->productServiceContract->findSingleProductForProductable($product);
        }
        return [
            'product' => $prod ?
                ProductResource::make($prod) :
                null,
            'related_product' => $relatedProduct ?
                ProductResource::make($relatedProduct) :
                null
        ];
    }
}
