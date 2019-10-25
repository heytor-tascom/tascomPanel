<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePainelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nm_painel');
            $table->string('ds_painel')->nullable();
            $table->string('nm_rota');
            $table->boolean('ativo')->default(1);
            $table->integer('nr_ordem');
            $table->enum('ambiente', ['PRD','HOM','DEV'])->comment('PRD - Produção | HOM - Homologação | DEV - Desenvolvimento');
            $table->bigInteger('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painel');
    }
}
