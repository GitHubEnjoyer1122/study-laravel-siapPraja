<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SchoolDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFile = base_path('database/seeds/db_akademik.sql');
        $sqlQueries = File::get($sqlFile);

        // Run the SQL queries
        DB::unprepared($sqlQueries);
    }
}
