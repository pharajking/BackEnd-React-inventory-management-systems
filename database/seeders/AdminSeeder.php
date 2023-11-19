<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name'=> 'Admin',
            'email'=> 'admin@fashion-club.com',
            'phone'=> '0762091547',
            'password'=> Hash::make('12345678'),
            'role_id'=> 1,
        ];
        User::create($data);
    }
}
