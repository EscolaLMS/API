<?php

namespace App\Services\EscolaLMS\Contracts;

use App\Models\CurriculumLecturesQuiz;
use Illuminate\Contracts\Auth\Authenticatable;

interface MediaContract
{
    public static function make(CurriculumLecturesQuiz $curriculumLecturesQuiz): MediaContract;

    public function get();

    public function getResource(): array;

    public function getUrl(): string;

    public function getLecture(): CurriculumLecturesQuiz;

    public function delete(bool $withoutTrashing = false): void;

    public function create($content, Authenticatable $user): self;
}
