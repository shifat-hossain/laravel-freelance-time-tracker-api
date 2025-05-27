<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $time_logs = [
            ['project_id' => '1', 'start_time' => '2025-05-21 22:00:00', 'description' => 'demo description', 'end_time' => '2025-05-22 03:00:00', 'hours' => 5.00, 'created_at' => '2025-05-21 22:00:00'],
            ['project_id' => '1', 'start_time' => '2025-05-22 22:00:00', 'description' => 'demo description', 'end_time' => '2025-05-23 03:00:00', 'hours' => 5.00, 'created_at' => '2025-05-22 22:00:00'],
            ['project_id' => '1', 'start_time' => '2025-05-23 22:00:00', 'description' => 'demo description', 'end_time' => '2025-05-24 03:00:00', 'hours' => 5.00, 'created_at' => '2025-05-23 22:00:00'],
            ['project_id' => '2', 'start_time' => '2025-05-24 22:00:00', 'description' => 'demo description', 'end_time' => '2025-05-25 03:00:00', 'hours' => 5.00, 'created_at' => '2025-05-24 22:00:00'],
            ['project_id' => '2', 'start_time' => '2025-05-25 22:00:00', 'description' => 'demo description', 'end_time' => '2025-05-26 03:00:00', 'hours' => 5.00, 'created_at' => '2025-05-25 22:00:00'],
            ['project_id' => '2', 'start_time' => '2025-05-26 22:00:00', 'description' => 'demo description', 'end_time' => '2025-05-27 03:00:00', 'hours' => 5.00, 'created_at' => '2025-05-26 22:00:00'],
        ];

        DB::table('time_logs')->insert($time_logs);
    }
}
