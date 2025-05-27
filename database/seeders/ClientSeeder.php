<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['user_id' => 1, 'name' => 'Client 1', 'email' => 'client@example.com', 'contact_person' => 'John Doe'],
            ['user_id' => 1, 'name' => 'Client 2', 'email' => 'client2@example.com', 'contact_person' => 'John Doe']
        ];

        DB::table('clients')->insert($clients);
    }
}
