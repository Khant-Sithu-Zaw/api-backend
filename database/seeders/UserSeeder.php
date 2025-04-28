<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Khant Sithu Zaw',
            'email' => 'khantsithuzaw253@gmail.com',
            'password' => Hash::make('khantsithuzaw253') // Encrypt the password
        ]);
    }
}
