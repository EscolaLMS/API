<?php

namespace App\Services;

use App\Services\Contracts\RegisterProductServiceContract;
use EscolaLms\Cart\Services\Contracts\ProductServiceContract;

class RegisterProductService implements RegisterProductServiceContract
{
    private ProductServiceContract $productServiceContract;
    public function __construct(
        ProductServiceContract $productServiceContract
    ) {
        $this->productServiceContract = $productServiceContract;
    }

    public function registerProductToResource(string $class, int $id): array
    {
        $product = $this->productServiceContract->findProductable(
            $class,
            $id
        );
        return ['product' => $this->productServiceContract->findSingleProductForProductable($product)];
    }
}
