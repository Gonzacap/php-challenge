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


    if ($capsule->getConnection()->getDatabaseName()) {
        echo "Database connection established successfully.\n";
    } else {
        echo "Error establishing database connection.\n";
        exit(1);
    }

    $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
    $migrator = new Migrator($repository, $capsule->getDatabaseManager(), new Filesystem());

    $migrationsPath = __DIR__ . '/../database/migrations';

    if (!is_dir($migrationsPath)) {
        throw new Exception("Migrations folder does not exist in the specified path: $migrationsPath");
    }

    $migrationFiles = glob("$migrationsPath/*.php");
    if (empty($migrationFiles)) {
        throw new Exception("No migration files found in the folder: $migrationsPath");
    }

    echo "Migration files found:\n";
    foreach ($migrationFiles as $file) {
        echo basename($file) . "\n";
    }

    // Run Migrations
    $migrator->run($migrationsPath);

    echo "Migrations Done!" . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
