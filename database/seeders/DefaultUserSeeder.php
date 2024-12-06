<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('email', 'temanhumas@gmail.com')->count() == 0) {
            $user = User::create([
                'name' => 'Teman Humas',
                'email' => 'temanhumas@gmail.com',
                'password' => bcrypt('temanhumas'),
                'email_verified_at' => now()
            ]);
            $user->creation_token = null;
            $user->save();
        }
    }
}
