<?php

namespace Database\Seeders;

use App\Models\Contenaire;
use Illuminate\Database\Seeder;

class ContenaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contenaire = Contenaire::create([
            'nom' => "40'",
            'volume' => 70,
            'isdefault' => 1
        ]);
        
        $contenaire->save();
    }
}
