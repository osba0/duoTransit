<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeCommande;

class TypeCommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typecmd = TypeCommande::create([
            'typcmd' => 'Médicament',
            'tcolor' => '#4e9a06',
            'etat'   => true
        ]);
        
        $typecmd->save();
    }
}
