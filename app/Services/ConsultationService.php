<?php

namespace App\Services;

use App\Services\Contracts\ConsultationServiceContract;
use EscolaLms\Cart\Enums\ProductType;
use EscolaLms\Cart\Models\Product;
use EscolaLms\Consultations\Enum\ConsultationTermStatusEnum;
use EscolaLms\Consultations\Models\ConsultationUserPivot;

class ConsultationService implements ConsultationServiceContract
{
    public function updateReportTerm(ConsultationUserPivot $consultationTerm): void
    {
        $product = Product::find($consultationTerm->product_id);
        if (isset($product) && $product->type === ProductType::BUNDLE) {
            \DB::transaction(function () use($consultationTerm) {
                $consultationTerm->executed_status = ConsultationTermStatusEnum::APPROVED;
                $consultationTerm->save();
            });
        }
    }
}
