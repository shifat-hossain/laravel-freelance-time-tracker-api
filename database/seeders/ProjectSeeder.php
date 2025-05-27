<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            ['title' => 'Project 1', 'client_id' => '1', 'description' => 'demo description', 'deadline' => '2025-05-29'],
            ['title' => 'Project 2', 'client_id' => '1', 'description' => 'demo description', 'deadline' => '2025-05-29'],
        ];

        DB::table('projects')->insert($projects);
    }
}
