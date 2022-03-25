<?php

use EscolaLms\Questionnaire\Models\QuestionnaireModelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordsToTableQuestionnaireModelType extends Migration
{
    public function up(): void
    {
        if (!QuestionnaireModelType::query()->where('title', '=', 'Webinar')->first()) {
            $questionnaireModelType = new QuestionnaireModelType([
                'title' => 'Webinar',
                'model_class' => 'EscolaLms\Webinar\Models\Webinar',
            ]);
            $questionnaireModelType->save();
        }

        if (!QuestionnaireModelType::query()->where('title', '=', 'Consultations')->first()) {
            $questionnaireModelType = new QuestionnaireModelType([
                'title' => 'Consultations',
                'model_class' => 'EscolaLms\Consultations\Models\Consultation',
            ]);
            $questionnaireModelType->save();
        }
    }

    public function down(): void
    {
        $questionnaireModelType = QuestionnaireModelType::query()->where('title', '=', 'Webinar')->first();
        if ($questionnaireModelType) {
            $questionnaireModelType->delete();
        }

        $questionnaireModelType = QuestionnaireModelType::query()->where('title', '=', 'Consultations')->first();
        if ($questionnaireModelType) {
            $questionnaireModelType->delete();
        }
    }
}
