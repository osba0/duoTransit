<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dries', function (Blueprint $table) {
            $table->id('dryidd');
            $table->integer('drycli');
            $table->string('drynom'); 
            $table->string('drydes'); 
            $table->boolean('dryeta');     
            $table->integer('dryuse'); // id user        
            $table->dateTime('drydcl', $precision = 0)->nullable();  
            $table->timestamp('drycre');  
            $table->timestamp('dryupd');
            //$table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dries');
    }
}
