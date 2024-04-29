<?php
require_once 'vendor/autoload.php';

// use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager;

class CreateMigrationsTable extends Migration
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

        if (!Manager::schema()->hasTable('migrations')) {

            Manager::schema()->create('migrations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('migration');
                $table->integer('batch');
                $table->timestamps();
            });
        }
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

        if (Manager::schema()->hasTable('migrations')) {
            Manager::schema()->dropIfExists('migrations');
        }
    }
}
