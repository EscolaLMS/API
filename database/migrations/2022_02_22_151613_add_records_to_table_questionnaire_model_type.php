<?php

use EscolaLms\Questionnaire\Models\QuestionnaireModelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordsToTableQuestionnaireModelType extends Migration
{
    public function up(): void
    {
        if (!QuestionnaireModelType::query()->where('title', '=', 'course')->first()) {
            $questionnaireModelType = new QuestionnaireModelType([
                'title' => 'course',
                'model_class' => 'EscolaLms\Courses\Models\Course',
            ]);
            $questionnaireModelType->save();
        }
    }

    public function down(): void
    {
        $questionnaireModelType = QuestionnaireModelType::query()->where('title', '=', 'course')->first();
        if ($questionnaireModelType) {
            $questionnaireModelType->delete();
        }
    }
}
