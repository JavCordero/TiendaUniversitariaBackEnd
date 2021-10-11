<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMermasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mermas', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('producto_codigo_interno');
            $table->integer('cantidad');
            $table->timestamp('fecha', $precision = 0);
            $table->timestamps();

            // llaves foraneas
            // $table->foreign('user_email')->references('email')->on('users');
            // $table->foreign('producto_codigo_interno')->references('codigo_interno')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mermas');
    }
}
