<?php

namespace App\Providers;

use App\Repositories\CategoriesRepository;
use App\Repositories\Contracts\CategoriesRepositoryContract;
use App\Repositories\Contracts\CourseFileRepositoryContract;
use App\Repositories\Contracts\CourseProgressRepositoryContract;
use App\Repositories\Contracts\CourseRepositoryContract;
use App\Repositories\Contracts\CourseVideoRepositoryContract;
use App\Repositories\Contracts\CurriculumLecturesQuizRepositoryContract;
use App\Repositories\Contracts\CurriculumSectionRepositoryContract;
use App\Repositories\Contracts\H5PContentRepositoryContract;
use App\Repositories\Contracts\InstructorRepositoryContract;
use App\Repositories\Contracts\TagRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\CourseFileRepository;
use App\Repositories\CourseProgressRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseVideoRepository;
use App\Repositories\CurriculumLecturesQuizRepository;
use App\Repositories\CurriculumSectionRepository;
use App\Repositories\H5PContentRepository;
use App\Repositories\InstructorRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use EscolaLms\Core\Providers\Injectable;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    use Injectable;

    const CONTRACTS = [
        CourseRepositoryContract::class => CourseRepository::class,
        InstructorRepositoryContract::class => InstructorRepository::class,
        CategoriesRepositoryContract::class => CategoriesRepository::class,
        CourseVideoRepositoryContract::class => CourseVideoRepository::class,
        TagRepositoryContract::class => TagRepository::class,
        CurriculumSectionRepositoryContract::class => CurriculumSectionRepository::class,
        CurriculumLecturesQuizRepositoryContract::class => CurriculumLecturesQuizRepository::class,
        CourseFileRepositoryContract::class => CourseFileRepository::class,
        UserRepositoryContract::class => UserRepository::class,
        H5PContentRepositoryContract::class => H5PContentRepository::class,
        CourseProgressRepositoryContract::class => CourseProgressRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->injectContract(self::CONTRACTS);
    }
}
