<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

try {


    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    /*
    echo $_ENV['DB_USER'] . "\n";
    echo $_ENV['DB_PASSWORD'] . "\n";
    echo $_ENV['DB_NAME'] . "\n";
    echo $_ENV['PROJECT_NAME'] . "\n";
    */


    $capsule = new Manager;
    $capsule->addConnection(require __DIR__ . '/../config/database.php');

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
    $migrator = new Migrator($repository, $capsule->getDatabaseManager(), new Filesystem());

    $pat = __DIR__ . '/../database/migrations';

    // Run Migrations
    $migrator->run($pat);

    echo "Migrations Done!" . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
