<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entite;
use App\Models\User;
use App\Models\UserRole;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('username', 'root')->first() === null) {
            $user = User::create([
                'firstname' => 'root',
                'lastname' => 'root',
                'username' => 'root',
                'email' => 'root@yomail.com',
                'password' => Hash::make('passer@123456'),
                'status' => 1,
                'entites_id' => Entite::take(1)->first()['id']
            ]);
            $user->addRole(UserRole::ROLE_ROOT);
            $user->save();
        }
    }
}
