<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;
use EscolaLms\Cart\Models\Product;
use EscolaLms\Consultations\Enum\ConsultationTermStatusEnum;
use EscolaLms\Core\Models\User;
use Illuminate\Database\Eloquent\Collection;

class Consultation extends \EscolaLms\Consultations\Models\Consultation implements Productable
{
    use ProductableTrait;

    public function attachToUser(User $user, int $quantity = 1, ?Product $product = null): void
    {
        for ($i = 1; $i <= $quantity; $i++) {
            $data = [
                'consultation_id' => $this->getKey(),
                'user_id' => $user->getKey(),
                'executed_status' => ConsultationTermStatusEnum::NOT_REPORTED,
                'product_id' => $product ? $product->getKey() : null
            ];
            parent::attachToConsultationPivot($data);
        }
    }

    public function getProductableAuthors(): Collection
    {
        return (new Collection([$this->author]))->filter();
    }
}
