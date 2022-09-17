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
            //$table->string('numContenaire')->nullable();
            //$table->foreignId('contenaires_id')->constrained();
            /*$table->string('plomb')->nullable();  
            $table->double('poidEmpote')->nullable(); 
            $table->double('volumeEmpote')->nullable(); 
            $table->double('colisEmpote')->nullable(); 
            $table->integer('nbreContenaire')->nullable();*/
            $table->foreignId('type_commandes_id')->constrained();
            $table->foreignId('entrepots_id')->constrained();
            $table->foreignId('users_id')->constrained();
            $table->foreignId('clients_id')->constrained();
            $table->boolean('reetat');
            $table->boolean('is_close');   
            //$table->text('rapport_pdf');  
            $table->date('date_depart')->nullable();  
            $table->date('date_arrivee')->nullable();  
            $table->text('complements_document')->nullable(); 
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
