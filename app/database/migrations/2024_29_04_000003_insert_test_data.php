<?php
require_once 'vendor/autoload.php';

// use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager;

class InsertTestData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $capsule = new Manager;
        $capsule->addConnection(require __DIR__ . '/../../config/database.php');
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $capsule->connection()->table('personas')->insert([
            'nombre' => 'Usuario',
            'apellido' => 'Test',
            'edad' => 30,
            'telefono' => '123456789',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $capsule->connection()->table('credenciales')->insert([
            'brand' => 'Test',
            'client_id' => 'C123',
            'secret_id' => 'S123',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $capsule = new Manager;
        $capsule->addConnection(require __DIR__ . '/../../config/database.php');
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $capsule->connection()->table('personas')->truncate();

        $capsule->connection()->table('credenciales')->truncate();
    }
}
