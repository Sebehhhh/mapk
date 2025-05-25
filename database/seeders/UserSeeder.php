<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Admin dummy
         User::create([
            'name' => 'Admin Dummy',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
        ]);

        // Siswa dummy
        User::create([
            'name' => 'Siswa Dummy',
            'email' => 'siswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}
