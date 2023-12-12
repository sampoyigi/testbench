<?php

namespace SamPoyigi\Testbench;

use Igniter\System\Classes\ExtensionManager;
use Igniter\System\Database\Seeds\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\File;
use function Orchestra\Testbench\artisan;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseTransactions;

    protected $enablesPackageDiscoveries = true;

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.connections.mysql.strict', false);
    }

    protected function getPackageProviders($app)
    {
        $app->afterResolving(ExtensionManager::class, function (ExtensionManager $manager) {
            $manager->loadExtension(realpath($_SERVER['PWD']));
            foreach (File::glob($_SERVER['PWD'].'/vendor/*/*/src/Extension.php') as $path) {
                $manager->loadExtension(dirname($path, 2));
            }
        });

        return [
            \Igniter\Flame\ServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        DatabaseSeeder::$seedDemo = true;

        if (!RefreshDatabaseState::$migrated) {
            artisan($this, 'igniter:up');

            $this->refreshApplication();

            RefreshDatabaseState::$migrated = true;
        }
    }
}
