<?php

namespace App\Providers;

use App\Events\CourseAssigned;
use App\Events\CourseCompleted;
use EscolaLms\Auth\Events\PasswordForgotten;
use App\Events\SectionCompleted;
use App\Events\UserLogged;
use App\Listeners\AttachOrderedCoursesToUser;
use App\Listeners\CreatePasswordResetToken;
use App\Listeners\CreateRelatedData;
use App\Listeners\CreateUserCart;
use App\Listeners\LoadUserCart;
use App\Listeners\NotifyOrderComplete;
use EscolaSoft\Shopping\Events\OrderPaid;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateRelatedData::class,
            CreateUserCart::class
        ],
        PasswordForgotten::class => [
            CreatePasswordResetToken::class,
        ],
        CourseCompleted::class => [

        ],
        SectionCompleted::class => [
        ],
        UserLogged::class => [
            LoadUserCart::class
        ],
        CourseAssigned::class => [
        ],
        OrderPaid::class => [
            AttachOrderedCoursesToUser::class,
            NotifyOrderComplete::class
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
