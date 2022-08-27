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
            $table->date('dateDebut');
            $table->date('dateCloture')->nullable();
            $table->foreignId('clients_id')->constrained();
            $table->foreignId('users_id')->constrained();
            $table->boolean('reetat');  
            $table->boolean('is_empote');  
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
