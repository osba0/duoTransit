<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('receptions', function (Blueprint $table) {
            $table->id('reidre');

            $table->double('refere'); // Reference FE
            $table->double('reecvr'); // Reference ECV
            $table->string('renufa'); // Numero facture
            $table->double('revafa'); // Valeur facture
            $table->string('refasc')->nullable(); // Facture Scanné

            $table->foreignId('fournisseurs_id')->constrained();
            $table->foreignId('type_commandes_id')->constrained();
            $table->foreignId('clients_id')->constrained();
            $table->foreignId('entrepots_id')->constrained();
            $table->foreignId('users_id')->constrained();
            $table->foreignId('entites_id')->constrained();
            $table->double('repoid'); // Poids
            $table->double('revolu'); // Volume
            $table->integer('renbcl')->nullable(); // Nombre colis
            $table->integer('renbpl')->nullable(); // Nombre pal
            $table->integer('reemba')->nullable(); // Emballage
            $table->boolean('reetat'); // Etat
            $table->string('reempl')->nullable();  // Emplacement
            $table->date('redali')->nullable();    // Date livraison
            $table->json('recmds')->nullable();    // liste des commandes receptionné 
            $table->string('rencmd'); // N° commande 
            $table->text('recomt')->nullable(); // Commentaires
            $table->string('douane')->nullable(); // N douane 
            $table->string('depalettisation')->nullable(); //Depaletisation 
            $table->string('numero_contenaire')->nullable(); // N douane   
            $table->boolean('isPreLoad')->default(0);
            $table->boolean('isLoad')->default(0);
            $table->integer('dossier_prechargements_id')->nullable();
            $table->integer('dossier_empotage_id')->nullable();
            $table->string('dossier_id')->nullable();
            $table->boolean('reinci')->default(0);
            $table->integer('priorite')->default(2);
            $table->timestamp('recrea');  
            $table->timestamp('reupda')->default(\DB::raw('CURRENT_TIMESTAMP'));






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
        Schema::dropIfExists('receptions');
    }
}
