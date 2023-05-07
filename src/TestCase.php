<?php

namespace Igniter\Testbench;

use function Orchestra\Testbench\artisan;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected static array $packageProviders;

    public static function definePackageProviders(array $providers)
    {
        static::$packageProviders = $providers;
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.connections.mysql.strict', false);
    }

    protected function getPackageProviders($app)
    {
        return static::$packageProviders;
    }

    protected function defineDatabaseMigrations()
    {
        artisan($this, 'igniter:up');
    }
}
