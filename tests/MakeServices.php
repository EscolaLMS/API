<?php

namespace Tests;

use App\Repositories\Contracts\CourseProgressRepositoryContract;
use EscolaLms\Categories\Services\Contracts\CategoryServiceContracts;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;

trait MakeServices
{
    public function courseService(): CourseServiceContract
    {
        return $this->courseService = app(CourseServiceContract::class);
    }

    public function courseProgressRepository(): CourseProgressRepositoryContract
    {
        return $this->courseProgressRepository = app(CourseProgressRepositoryContract::class);
    }

    public function categoryService(): CategoryServiceContracts
    {
        return $this->courseService = app(CategoryServiceContracts::class);
    }
}
