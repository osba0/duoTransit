<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DossierPrechargements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossier_prechargements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contenaires_id')->constrained();
            $table->integer('nbreContenaire')->nullable();
            $table->integer('type_commandes_id')->constrained(); 
            $table->foreignId('entites_id')->constrained();  
            $table->foreignId('users_id')->constrained();
            $table->foreignId('clients_id')->constrained();
            $table->boolean('reetat');  
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
         Schema::dropIfExists('dossier_prechargement');
    }
}
