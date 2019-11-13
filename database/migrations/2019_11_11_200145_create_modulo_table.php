<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloTable extends Migration
{
    public function up()
    {
        Schema::create('modulo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nm_modulo', 300);
            $table->string('ds_icone', 100)->nullable();
            $table->string('ds_cor', 100)->default("text-success");
            $table->integer('nr_ordem')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modulo');
    }
}
