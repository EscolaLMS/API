<?php

namespace Tests\Integrations;

use Tests\TestCase;

class SwaggerTest extends TestCase
{
    public function test_swagger_generate(): void
    {
        $this->artisan('l5-swagger:generate')
            ->expectsOutput('Regenerating docs default')
            ->assertExitCode(0);
    }
}
