<?php

namespace SamPoyigi\Testbench;

use Igniter\System\Classes\ExtensionManager;
use function Orchestra\Testbench\artisan;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.connections.mysql.strict', false);
    }

    protected function getPackageProviders($app)
    {
        $app->afterResolving(ExtensionManager::class, function ($manager) {
            $manager->loadExtension(realpath($_SERVER['PWD']));
        });

        return [
            \Igniter\Flame\ServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        artisan($this, 'igniter:up');
    }
}
