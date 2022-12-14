<?php

namespace Database\Seeders;

use App\Models\Entite;
use App\Models\Entrepot;
use App\Models\Contenaire;
use Illuminate\Database\Seeder;

class EntiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entrepot = [];
        $contenaire = [];

        $entrepot[] = Entrepot::take(1)->first()['id'];
        $contenaire[] = Contenaire::where('isDefault', true)->first()['id'];

        $entite = Entite::create([
            'nom' => 'Marine Plus',
            'slug' => 'marineplus',
            'adresse' => '29 AVENUE DE GENEVE, France',
            'telephone' => '00 00 00 00',
            'fax' => '00 00 00 00',
            'email' => 'marineplus@yopmail.com',
            'etat' => 1,
            'logo' => 'logo_1658251336.png', // Logo Marine plus
            'entrepots_id' => $entrepot,
            'contenaires_id' => $contenaire
        ]);
        
        $entite->save();
    }
}
