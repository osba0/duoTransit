<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
            TypeCommandeSeeder::class,
            EntrepotSeeder::class,
            ContenaireSeeder::class,
            EntiteSeeder::class,
            UserSeeder::class
        ]);
    }
}
