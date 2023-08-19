<?php

namespace Sven\LaravelChainedCommands\Tests;

use Orchestra\Testbench\TestCase as TestBenchTestCase;
use Sven\LaravelChainedCommands\ServiceProvider;

abstract class TestCase extends TestBenchTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
