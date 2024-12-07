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

        $koorTim = User::create([
            'name' => 'Upin',
            'email' => 'upin@helper.app',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now()
        ]);
        $koorTim->creation_token = null;
        $koorTim->save();

        $anggotaTim = User::create([
            'name' => 'Ipin',
            'email' => 'ipin@helper.app',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now()
        ]);
        $anggotaTim->creation_token = null;
        $anggotaTim->save();


    }
}
