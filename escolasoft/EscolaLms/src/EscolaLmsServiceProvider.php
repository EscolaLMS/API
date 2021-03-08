<?php

namespace EscolaLms\Core;

use EscolaLms\Core\Providers\Injectable;
use EscolaLms\Core\Repositories\AttachmentRepository;
use EscolaLms\Core\Repositories\Contracts\AttachmentRepositoryContract;
use EscolaLms\Core\Services\AttachmentService;
use EscolaLms\Core\Services\Contracts\AttachmentServiceContract;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class EscolaLmsServiceProvider extends ServiceProvider
{
    use Injectable;

    private const CONTRACTS = [
        AttachmentServiceContract::class => AttachmentService::class,
        AttachmentRepositoryContract::class => AttachmentRepository::class
    ];

    public function register()
    {
        $this->injectContract(self::CONTRACTS);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'escola-lms');

        $this->registerComponents();
    }

    private function registerComponents(): void
    {
        Blade::componentNamespace('EscolaSoft\\EscolaLms\\View\\Components\\Forms', 'escola-form');
    }
}
