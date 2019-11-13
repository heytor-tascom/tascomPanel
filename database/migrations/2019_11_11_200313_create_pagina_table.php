<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaginaTable extends Migration
{
    public function up()
    {
        Schema::create('pagina', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nm_pagina');
            $table->integer('modulo_id')->unsigned();
            $table->foreign('modulo_id')->references('id')->on('modulo')->onDelete('cascade');
            $table->integer('pagina_id')->unsigned()->nullable();
            $table->foreign('pagina_id')->references('id')->on('pagina')->onDelete('cascade');
            $table->string('ds_icone')->nullable();
            $table->string('nm_rota')->nullable();
            $table->integer('nr_ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagina');
    }
}
