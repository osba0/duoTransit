<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContenairesEmpotage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contenaires_empotage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empotages_id')->constrained();
            $table->string('numContenaire')->nullable();
            $table->foreignId('contenaires_id')->constrained();
            $table->string('plomb')->nullable();  
            $table->double('poidEmpote')->nullable(); 
            $table->double('volumeEmpote')->nullable(); 
            $table->double('colisEmpote')->nullable(); 
            $table->boolean('etat');
            $table->text('photos_chargement')->nullable();
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
        Schema::dropIfExists('contenaires_empotage');
    }
}
