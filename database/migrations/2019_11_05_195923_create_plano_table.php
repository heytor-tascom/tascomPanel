<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanoTable extends Migration
{
    public function up()
    {
        Schema::create('plano', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nm_plano');
            $table->text('ds_plano');
            $table->text('vl_plano');
            $table->text('nr_periodo');
            $table->boolean('ativo')->default(true);
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('plano');
    }
}
