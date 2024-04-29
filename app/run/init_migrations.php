<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

try {

    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

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

    Manager::schema()->create('migrations', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('migration');
        $table->integer('batch');
        $table->timestamps();
    });

    echo "Migrations table created";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
