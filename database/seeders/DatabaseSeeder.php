<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run Seeders to populate the database with a few example tasks
     */
    public function run(): void
    {
        $this->call([
            TasksSeeder::class
        ]);
    }
}
