<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanoProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('plano_produto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('plano_id')->unsigned();
            $table->foreign('plano_id')->references('id')->on('plano');
            $table->bigInteger('produto_id')->unsigned();
            $table->foreign('produto_id')->references('id')->on('produto');
            $table->timestamps();
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plano_produto');
    }
}
