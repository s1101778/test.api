<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UvaTopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $file_path=resource_path('sql/UvaTopic.sql');
        DB::unprepated(
            file_get_contents($file_path)
        );
    }
}
