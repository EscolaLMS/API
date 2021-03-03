<?php

namespace App\ValueObjects\Contracts;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

interface CourseProgressCollectionContract
{
    public function getUser(): Authenticatable;

    public function getCourse(): Course;

    public function start(): self;

    public function isStarted(): bool;

    public function isFinished(): bool;

    public function getProgress(): Collection;

    public function setProgress(array $progress): self;

    public function getTotalSpentTime(): int;

    public function getFinishDate(): ?Carbon;
}
