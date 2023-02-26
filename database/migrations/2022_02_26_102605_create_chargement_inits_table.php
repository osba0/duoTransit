<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargementInitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chargement_creations', function (Blueprint $table) {
            $table->id();
            $table->string('numDossier');
            $table->integer('type_commandes_id')->constrained(); 
            $table->integer('entrepots_id')->constrained(); 
            $table->date('dateDebut');
            $table->date('dateCloture')->nullable();
            $table->string('booking')->nullable();
            $table->string('nomNavire')->nullable();
            $table->string('terminalRetour')->nullable();
            $table->date('dateDepart')->nullable();
            $table->date('dateArrivee')->nullable();
            $table->foreignId('clients_id')->constrained();
            $table->foreignId('entites_id')->constrained();
            $table->foreignId('users_id')->constrained();
            $table->boolean('reetat');  
            $table->boolean('is_empote');  
            $table->boolean('on_empote'); // new bloquer le prechargement quand c'est en cours d'empotage 
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
        Schema::dropIfExists('chargement_creations');
    }
}
