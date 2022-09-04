<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fournisseur;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fournisseur = Fournisseur::create([
            'fonmfo' => 'PLANET PHARMA',
            'foadrs' => 'paris, France',
            'fotele' => '000 000 000 000',
            'foetat' => 1
        ]);
        
        $fournisseur->save();
    }
}
