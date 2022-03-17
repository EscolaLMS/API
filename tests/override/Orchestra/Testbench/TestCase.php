<?php

namespace Orchestra\Testbench;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
