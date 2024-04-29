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
    $capsule->addConnection([
        'driver'    => 'pgsql',
        'host'      => $_ENV['PROJECT_NAME'] . '-' . $_ENV['DB_NAME'],
        'port'      => '5432',
        'database'  => $_ENV['DB_NAME'],
        'username'  => $_ENV['DB_USER'],
        'password'  => $_ENV['DB_PASSWORD'],
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
    $migrator = new Migrator($repository, $capsule->getDatabaseManager(), new Filesystem());

    // Run Migrations
    $migrator->run(__DIR__ . '/database/migrations');

    echo "Migrations Done!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
