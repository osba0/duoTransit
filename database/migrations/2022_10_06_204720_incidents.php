<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Incidents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('commandes');
            
            $table->string('objet')->nullable();
            $table->string('commentaire')->nullable();
            $table->foreignId('users_id')->constrained();
            $table->foreignId('clients_id')->constrained();
            $table->foreignId('entites_id')->constrained();  
            $table->text('photos');
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
       Schema::dropIfExists('incidents');
    }
}
