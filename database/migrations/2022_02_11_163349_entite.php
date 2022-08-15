<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Entite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entites', function (Blueprint $table) {
            $table->id('id');
            $table->string('slug')->unique();
            $table->string('nom');
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('logo')->nullable();
            $table->string('etat');
            $table->text('entrepots_id')->nullable(); 
            $table->text('contenaires_id')->nullable(); 
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
         Schema::dropIfExists('entites');
    }
}
