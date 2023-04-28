<?php

namespace Kerigard\LaravelUtils\Tests;

use Kerigard\LaravelUtils\UtilsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [UtilsServiceProvider::class];
    }
}
