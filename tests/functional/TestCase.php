<?php

declare(strict_types=1);

namespace Smile\GdprDump\Tests\Functional;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Smile\GdprDump\AppKernel;
use Smile\GdprDump\Config\Config;
use Smile\GdprDump\Config\Loader\ConfigLoader;
use Smile\GdprDump\Database\Config as DatabaseConfig;
use Smile\GdprDump\Database\Database;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class TestCase extends BaseTestCase
{
    protected static ?AppKernel $kernel = null;
    protected static ?Database $database = null;

    /**
     * Get the absolute path of the application.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * Get a resource file.
     *
     * @param string $fileName
     * @return string
     */
    protected static function getResource(string $fileName): string
    {
        return __DIR__ . '/Resources/' . $fileName;
    }

    /**
     * Get the database wrapper.
     *
     * @return Database
     */
    protected static function getDatabase(): Database
    {
        if (static::$database === null) {
            // Parse the config file
            /** @var ConfigLoader $loader */
            $loader = static::getContainer()->get('dumper.config_loader');
            $loader->load(static::getResource('config/templates/test.yaml'));

            /** @var Config $config */
            $config = static::getContainer()->get('dumper.config');
            $config->compile();

            // Initialize the shared connection
            $connectionParams = $config->get('database');
            $connectionParams['dbname'] = $connectionParams['name'];
            unset($connectionParams['name']);
            static::$database = new Database(new DatabaseConfig($connectionParams));

            // Create the tables
            $connection = static::$database->getConnection();
            $queries = file_get_contents(static::getResource('db/test.sql'));
            $statement = $connection->prepare($queries);
            $statement->execute();
        }

        return static::$database;
    }

    /**
     * Get the DI container.
     *
     * @return ContainerInterface
     */
    protected static function getContainer(): ContainerInterface
    {
        if (static::$kernel === null) {
            static::$kernel = new AppKernel();
            static::$kernel->boot();
        }

        return static::$kernel->getContainer();
    }
}
