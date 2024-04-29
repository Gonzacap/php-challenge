<?php
require_once 'vendor/autoload.php';

// use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager;

class CreateCredencialesTable  extends Migration
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

        if (!Manager::schema()->hasTable('credenciales')) {
            Manager::schema()->create('credenciales', function (Blueprint $table) {
                $table->increments('id');
                $table->string('brand');
                $table->string('client_id');
                $table->string('secret_id');
                $table->timestamps();
            });
        }

        echo "Migration CreateCredencialesTable executed successfully!\n";
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

        if (Manager::schema()->hasTable('credenciales')) {
            Manager::schema()->drop('credenciales');
        }
    }
}
