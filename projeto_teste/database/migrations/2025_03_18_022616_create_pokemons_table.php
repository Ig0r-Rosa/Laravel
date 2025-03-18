<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsTable extends Migration
{
    public function up()
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('height');
            $table->integer('weight');
            $table->integer('base_experience');
            $table->json('abilities');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pokemons');
    }
}