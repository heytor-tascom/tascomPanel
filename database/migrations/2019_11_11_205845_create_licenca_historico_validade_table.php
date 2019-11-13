<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicencaHistoricoValidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenca_historico_validade', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('licenca_id')->unsigned();
            $table->foreign('licenca_id')->references('id')->on('licenca');
            $table->timestamp('dt_validade_anterior')->nullable();
            $table->timestamp('dt_validade_atual');
            $table->string('ds_motivo')->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('licenca_historico_validade');
    }
}
