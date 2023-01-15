<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_commandes', function (Blueprint $table) {
            $table->id();
            $table->integer('type_commande');
            $table->string('fournisseur');
            $table->string('commandes')->unique();
            $table->string('client'); // duopharm ou autre client 
            $table->date('date_transmission');
            $table->string('user_import'); 
            $table->boolean('etat_cmd')->default(0); 
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
        Schema::dropIfExists('import_commandes');
    }
}
