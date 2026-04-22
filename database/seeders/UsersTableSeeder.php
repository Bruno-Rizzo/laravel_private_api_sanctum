<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Consumidor 1',
                'email'             => 'consumidor1@gmail.com',
                'email_verified_at' => now(),
                'password'          => bcrypt('abc123')
            ],
            [
                'name'              => 'Consumidor 2',
                'email'             => 'consumidor2@gmail.com',
                'email_verified_at' => now(),
                'password'          => bcrypt('abc123')
            ],
            [
                'name'              => 'Consumidor 3',
                'email'             => 'consumidor3@gmail.com',
                'email_verified_at' => now(),
                'password'          => bcrypt('abc123')
            ]
        ];

        foreach($users as $user){
            User::create($user);
        };
    }
}
