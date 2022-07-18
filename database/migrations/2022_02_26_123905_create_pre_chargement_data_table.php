<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreChargementDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_chargement_data', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('chargement_inits_numDossier')->constrained(); 

            $table->integer('chargement_inits_numDossier')->constrained();
            $table->foreign('chargement_inits_numDossier')->references('numDossier')->on('chargement_inits');  
            $table->double('poidEmpote'); 
            $table->double('volumeEmpote'); 
            $table->double('colisEmpote'); 
            $table->foreignId('type_commandes_id')->constrained();
            $table->integer('nbCont40Pieds')->nullable(); // Nombre contenaire 40 pieds
            $table->integer('nbCont20Pieds')->nullable(); // Nombre contenaire 20 pieds
            $table->foreignId('users_id')->constrained();
            $table->foreignId('clients_id')->constrained();
            $table->json('commandes')->nullable();  
            $table->boolean('reetat'); // Etat
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
        Schema::dropIfExists('pre_chargement_data');
    }
}
