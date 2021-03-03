<?php

namespace Tests\Unit\Models;

use App\Enum\MediaType;
use App\Models\CurriculumLecturesQuiz;
use PHPUnit\Framework\TestCase;

class CurriculumLecturesQuizTest extends TestCase
{
    public function test_HasMediaAttribute_is_false_without_media()
    {
        $curriculumLecturesQuiz = new CurriculumLecturesQuiz();
        $this->assertFalse($curriculumLecturesQuiz->hasMedia);
    }

    public function test_HasMediaAttribute_is_true_with_media()
    {
        $curriculumLecturesQuiz = new CurriculumLecturesQuiz([
            'media_type' => MediaType::VIDEO
        ]);
        $this->assertTrue($curriculumLecturesQuiz->hasMedia);
    }
}
