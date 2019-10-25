<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_produto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nm_tipo_produto');
            $table->boolean('ativo')->default(1);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('tipo_produto');
    }
}
