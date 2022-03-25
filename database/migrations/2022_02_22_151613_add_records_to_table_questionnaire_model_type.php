<?php

use EscolaLms\Questionnaire\Models\QuestionnaireModelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordsToTableQuestionnaireModelType extends Migration
{
    public function up(): void
    {
        if (!QuestionnaireModelType::query()->where('title', '=', 'Course')->first()) {
            $questionnaireModelType = new QuestionnaireModelType([
                'title' => 'Course',
                'model_class' => 'EscolaLms\Courses\Models\Course',
            ]);
            $questionnaireModelType->save();
        }
    }

    public function down(): void
    {
        $questionnaireModelType = QuestionnaireModelType::query()->where('title', '=', 'Course')->first();
        if ($questionnaireModelType) {
            $questionnaireModelType->delete();
        }
    }
}
