<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creates 10 example tasks with a random case number and random due date up to 7 days from now.
        Task::factory()
            ->count(10)
            ->create();
    }
}
