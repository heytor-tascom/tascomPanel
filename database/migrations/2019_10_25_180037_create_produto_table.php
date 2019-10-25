<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoTable extends Migration
{
    
    public function up()
    {
        Schema::create('produto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nm_produto');
            $table->string('ds_produto')->nullable();
            $table->bigInteger('tipo_produto_id')->unsigned();
            $table->foreign('tipo_produto_id')->references('id')->on('tipo_produto');
            $table->string('nm_rota');
            $table->boolean('ativo')->default(1);
            $table->integer('nr_ordem');
            $table->enum('ambiente', ['PRD','HOM','DEV'])->comment('PRD - Produção | HOM - Homologação | DEV - Desenvolvimento');
            $table->bigInteger('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

  
    public function down()
    {
        Schema::dropIfExists('produto');
    }
}
