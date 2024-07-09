<?php

namespace SamPoyigi\Testbench;

use Igniter\Main\Classes\ThemeManager;
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
        $app->afterResolving(ExtensionManager::class, function(ExtensionManager $manager) {
            $currentPath = realpath($_SERVER['PWD']);
            if (File::exists($currentPath.'/src/Extension.php')) {
                $manager->loadExtension($currentPath);
            }

            foreach (File::glob($_SERVER['PWD'].'/vendor/*/*/src/Extension.php') as $path) {
                rescue(fn() => $manager->loadExtension(dirname($path, 2)));
            }
        });

        $app->afterResolving(ThemeManager::class, function(ThemeManager $manager) {
            $currentPath = realpath($_SERVER['PWD']);
            if (File::exists($currentPath.'/theme.php')) {
                $manager->bootTheme($manager->loadTheme($currentPath));
            }

            foreach (File::glob($_SERVER['PWD'].'/vendor/*/*/theme.php') as $path) {
                rescue(fn() => $manager->bootTheme($manager->loadTheme(dirname($path))));
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
            artisan($this, 'igniter:down');
            artisan($this, 'igniter:up');

            $this->refreshApplication();

            RefreshDatabaseState::$migrated = true;
        }
    }
}
