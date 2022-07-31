<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id');
            $table->string('slug')->unique();
            $table->string('clnmcl');
            $table->string('cladcl');
            $table->string('cltele');
            $table->string('clmail');
            $table->string('cllogo')->nullable(); 
            $table->text('clfocl')->nullable();  
            $table->text('cltyco')->nullable();  
            $table->text('clenti')->nullable(); 
            $table->string('pays');
            $table->string('cletat'); 
            $table->timestamp('clcrea');
            $table->timestamp('clupda');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
