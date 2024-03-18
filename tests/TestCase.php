<?php

namespace Kerigard\LaravelCommands\Tests;

use Kerigard\LaravelCommands\CommandsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [CommandsServiceProvider::class];
    }
}
