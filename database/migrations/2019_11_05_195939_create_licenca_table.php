<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicencaTable extends Migration
{
    public function up()
    {
        Schema::create('licenca', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('plano_id')->unsigned();
            $table->foreign('plano_id')->references('id')->on('plano');
            $table->bigInteger('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('cliente');
            $table->date('dt_validade');
            $table->boolean('ativo')->default(true); 
            $table->timestamps();
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('licenca');
    }
}
