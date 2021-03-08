<?php

namespace EscolaLms\Core\Providers;

trait Injectable
{
    private function injectContract(array $contracts): void
    {
        foreach ($contracts as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
