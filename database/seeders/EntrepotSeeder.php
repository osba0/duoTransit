<?php

namespace Database\Seeders;

use App\Models\Entrepot;
use Illuminate\Database\Seeder;

class EntrepotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entrepot = Entrepot::create([
            'titulaire' => 'Michéle',
            'email' => 'michele@yopmail.com',
            'telephone' => '000 000 000 000',
            'nomEntrepot' => 'CNM',
            'adresse' => 'rue 10, Dakar - Senegal'
        ]);
        
        $entrepot->save();
    }
}
