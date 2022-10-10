<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocadorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locadoras', function (Blueprint $table) {
            $table->id();
            $table->string('nome_fantasia');
            $table->string('razao_social');
            $table->string('cnpj');
            $table->string('email');
            $table->string('telefone');
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
        Schema::dropIfExists('locadoras');
    }
}
