<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpotagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empotages', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->constrained();
            $table->foreignId('type_commandes_id')->constrained();
            $table->foreignId('entrepots_id')->constrained();
            $table->foreignId('users_id')->constrained();
            $table->foreignId('clients_id')->constrained();
            $table->foreignId('entites_id')->constrained();
            $table->boolean('reetat');
            $table->boolean('is_close');   
            $table->date('date_depart')->nullable();  
            $table->date('date_arrivee')->nullable();  
            $table->text('complements_document')->nullable(); 
            $table->text('autres_document')->nullable(); 
            $table->text('declaration_douane')->nullable(); 
            $table->string('numDocim')->nullable(); 
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
        Schema::dropIfExists('empotages');
    }
}
