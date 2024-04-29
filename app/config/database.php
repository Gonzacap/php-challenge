<?php

return [
    'driver'    => 'pgsql',
    'host'      => $_ENV['PROJECT_NAME'] . '-' . $_ENV['DB_NAME'],
    'port'      => '5432',
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];
