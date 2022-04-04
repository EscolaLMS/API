<?php

use EscolaLms\Questionnaire\Models\QuestionnaireModelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordsWebinarToTableQuestionnaireModelType extends Migration
{
    public function up(): void
    {
        if (!QuestionnaireModelType::query()->where('title', '=', 'webinar')->first()) {
            $questionnaireModelType = new QuestionnaireModelType([
                'title' => 'webinar',
                'model_class' => 'EscolaLms\Webinar\Models\Webinar',
            ]);
            $questionnaireModelType->save();
        }

        if (!QuestionnaireModelType::query()->where('title', '=', 'consultations')->first()) {
            $questionnaireModelType = new QuestionnaireModelType([
                'title' => 'consultations',
                'model_class' => 'EscolaLms\Consultations\Models\Consultation',
            ]);
            $questionnaireModelType->save();
        }
    }

    public function down(): void
    {
        QuestionnaireModelType::query()->where('title', '=', 'webinar')->delete();
        QuestionnaireModelType::query()->where('title', '=', 'consultations')->delete();
    }
}
