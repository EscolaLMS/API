<?php

namespace EscolaSoft\EscolaLms\Providers;

trait Injectable
{
    private function injectContract(array $contracts): void
    {
        foreach ($contracts as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
