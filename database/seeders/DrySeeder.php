<?php

namespace Database\Seeders;

use App\Models\Dry;
use Illuminate\Database\Seeder;

class DrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Dry::factory()->times(55)->create();
    }
}
