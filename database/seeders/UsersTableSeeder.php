<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'admin@example.com',
                'email_verified_at' => '2025-01-06 00:34:22',
                'password' => '$2y$12$g.A2OFrFPDc3vsCY1eVYneUPNFInc6ahhQjfm7bwqixuTzFpXXtRG',
                'remember_token' => 'IaZTKIi6v9iU42FleKmd0yfHFy1EwLmfTMro0S4lPCClS6qWAJo2UWLiBrsn',
                'created_at' => '2025-01-06 00:34:23',
                'updated_at' => '2025-01-06 00:34:23',
                'profile_photo' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Lutfi Idham',
                'email' => 'idham@mail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$JaZSDOa2G38lG9Y9vAaDO.WwbpIgI805wix.D5teuTahr4NlZSTNe',
                'remember_token' => 'A4Bi3jsKu7TDA1WCCN84eUSqyMBeO8r8ttitTF9UwF3tz1hv7bsXw6NPRrV7',
                'created_at' => '2025-01-06 01:08:39',
                'updated_at' => '2025-01-06 01:08:39',
                'profile_photo' => NULL,
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'Ndaru',
                'email' => 'ndaru@email.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$hknEDTEG8aeeNW7UQ5421.2tHxwduCKrcKdYRl60JjSKJQghCGxSS',
                'remember_token' => NULL,
                'created_at' => '2025-01-22 14:47:49',
                'updated_at' => '2025-01-22 14:47:49',
                'profile_photo' => NULL,
            ),
        ));
        
        
    }
}