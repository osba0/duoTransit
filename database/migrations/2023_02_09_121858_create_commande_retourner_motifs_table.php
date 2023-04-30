<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeRetournerMotifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commande_retourner_motifs', function (Blueprint $table) {
            $table->id();
            $table->text('motif')->nullable();
            $table->string('username')->nullable(); // utilisateur qui a crée la commande
            $table->string('user')->nullable(); // utilisateur qui a retourné la commande
            $table->string('datecmd')->nullable();  
            $table->string('numcommande')->nullable();
            $table->integer('idreception')->nullable();
            $table->timestamp('datemotif');  
            $table->timestamp('dateupdate');  

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande_retourner_motifs');
    }
}
