<?php
require_once 'vendor/autoload.php';

// use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager;

class CreatePersonasTable extends Migration
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

        if (!Manager::schema()->hasTable('personas')) {
            Manager::schema()->create('personas', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nombre');
                $table->string('apellido');
                $table->integer('edad');
                $table->string('telefono');
                $table->timestamps();
            });
        }

        echo "Migration CreatePersonasTable executed successfully!\n";
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

        if (Manager::schema()->hasTable('personas')) {
            Manager::schema()->drop('personas');
        }
    }
}
