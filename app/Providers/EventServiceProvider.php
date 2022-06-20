<?php

namespace App\Providers;

use App\Listeners\UpdateReportTermListener;
use EscolaLms\Consultations\Events\ReportTerm;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ReportTerm::class => [
            UpdateReportTermListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
