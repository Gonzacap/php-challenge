<?php
require_once 'vendor/autoload.php';

// use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
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

        DB::table('personas')->insert([
            'nombre' => 'Usuario',
            'apellido' => 'Test',
            'edad' => 30,
            'telefono' => '123456789',
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        DB::table('credenciales')->insert([
            'brand' => 'Test',
            'client_id' => 'C123',
            'secret_id' => 'S123',
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
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

        DB::table('personas')->truncate();

        DB::table('credenciales')->truncate();
    }
}
