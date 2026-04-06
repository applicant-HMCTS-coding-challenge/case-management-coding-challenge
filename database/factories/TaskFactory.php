<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;

#[UseModel(Task::class)]
class TaskFactory extends Factory
{
    /**
     * Definition for Example tasks to populate the database with
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $caseNumber = rand(100, 10000);

        return [
            'title' => 'Example Task #' . $caseNumber,

            /** Description prompt generated using ChatGPT: write a 100 word example task for a caseworker. */
            'description' => 'Conduct a comprehensive client intake assessment to determine immediate needs, risks, and eligibility for support services. Gather background information through interviews, review existing records, and coordinate with healthcare providers, housing agencies, and community organizations. Develop an individualized action plan outlining goals, timelines, and required interventions. Schedule follow-up meetings to monitor progress, address barriers, and adjust services as needed. Maintain accurate case notes and ensure confidentiality in all documentation. Provide clear communication to clients about available resources, rights, and responsibilities, while advocating on their behalf to secure stable housing, financial assistance, and access to mental health or employment programs when required.',

            'due_date' => Carbon::now('UTC')->addDays(rand(1, 7)),
            'case_number' => $caseNumber
        ];
    }
}
