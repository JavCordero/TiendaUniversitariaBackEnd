<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            // $table->id();
            $table->timestamps();

            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('codigo_interno')->primary();
            $table->string('codigo_barra')->unique();
            $table->string('categoria');
            $table->string('imagen')->nullable();
            $table->integer('precio');
            $table->integer('cantidad');
            $table->integer('stock_critico');
            $table->timestamp('fecha_notificacion', $precision = 0)->nullable();
            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
