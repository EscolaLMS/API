<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\HorizonCheck;
use Spatie\Health\Checks\Checks\RedisCheck;

class HealthCheckProvider extends ServiceProvider
{
    public function register()
    {
        $checks = [];
        $checks[] = DatabaseCheck::new();
        $checks[] = RedisCheck::new();

        Health::checks($checks);

        /**
         * TODO: add more checkchecks
        Health::checks([
            HorizonCheck::new(),
        ]);
         */
    }
}
