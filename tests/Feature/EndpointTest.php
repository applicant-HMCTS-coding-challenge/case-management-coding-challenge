<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Container\Attributes\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the initial database seeder works correctly
     */
    public function test_database_seeder(): void
    {
        $this->assertDatabaseEmpty('tasks');

        $this->seed();

        $this->assertDatabaseCount('tasks', 10);
        
        $task = Task::where('id', 1)->first();
        
        $this->assertEquals($task->status, 1);
    }

    /**
     * Test that tasks can be retrieved through the TaskController:index and TaskController:show endpoints
     */
    public function test_tasks_can_be_retrieved(): void
    {
        $this->seed();

        // Compare the first task in the database to the show API response for that task
        $task = Task::where('id', 1)->first();
        $response = $this->getJson('/api/tasks/1');
        $response->assertStatus(200);

        // Check if all fields for the task match what is expected
        $this->assertEquals($task->title, $response->json()['data']['title']);
        $this->assertEquals($task->case_number, $response->json()['data']['case_number']);
        $this->assertEquals($task->due_date . 'Z', $response->json()['data']['due_date']);
        $this->assertEquals($task->description, $response->json()['data']['description']);
        $this->assertEquals(Task::TASK_STATUS[$task->status], $response->json()['data']['task_status']);
        $this->assertEquals(null, $response->json()['data']['finalised_date']);
        $this->assertEquals(false, $response->json()['data']['overdue']);

        // Compare all tasks in the database to the API response for index
        $tasks = Task::all();
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200);

        // Assigned tasks should be equal to the total number of tasks
        $this->assertEquals(count($tasks), $response->json()['data']['assignedTasks']);
        
        // All tasks are pending
        $this->assertEquals(count($tasks), count($response->json()['data']['tasks']['Pending']));

        $task->status = 2;
        $task->save();

        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200);
        
        // Check that the response returns correctly now that one task is in progress
        $this->assertEquals(count($tasks)-1, count($response->json()['data']['tasks']['Pending']));
        $this->assertEquals(1, count($response->json()['data']['tasks']['In Progress']));

        $this->assertEquals(1, $response->json()['data']['tasks']['In Progress'][0]['id']);
    }

    /**
     *  Test that tasks can be created through the TaskController::store endpoint
     */
    public function test_tasks_can_be_created(): void
    {
        /** @var Task */
        $task = Task::factory()->make();

        $formData = [
            'title' => $task->title,
            'due_date' => $task->due_date->toDateTimeString(),
            'case_number' => $task->case_number,
            'description' => $task->description,
            'timezone' => 'UTC'
        ];

        $response = $this->postJson('/api/tasks', $formData);

        $response->assertStatus(200)
                 ->assertJson([
                    "data" => ["id" => 1]
                 ]);
        
        $createdTask = Task::where('id', 1)->first();

        $this->assertEquals($task->title, $createdTask->title);
        $this->assertEquals($task->due_date->toDateTimeString(), $createdTask->due_date);
        $this->assertEquals($task->case_number, $createdTask->case_number);
        $this->assertEquals($task->description, $createdTask->description);
    }

    /**
     * Test that validation errors are successfully presented when creating tasks
     */
    public function test_task_create_validation(): void
    {
        /** @var Task */
        $task = Task::factory()->make();

        // Test 1: Only send title.
        $formData = [
            'title' => $task->title
        ];

        $response = $this->postJson('/api/tasks', $formData);

        $response->assertStatus(422)
                 ->assertJson(['message' => 'The case number field is required. (and 2 more errors)']);

        // Test 2: Only send title, make title too long.
        $formData = [
            'title' => bin2hex(random_bytes(20))
        ];

        $response = $this->postJson('/api/tasks', $formData);

        $response->assertStatus(422)
                 ->assertJson(['message' => 'The title field must not be greater than 30 characters. (and 3 more errors)']);

        // Test 3: Send due date in the past
        $formData = [
            'title' => $task->title,
            'case_number' => $task->case_number,
            'due_date' => Carbon::yesterday(),
            'timezone' => 'UTC'
        ];

        $response = $this->postJson('/api/tasks', $formData);

        $response->assertStatus(422)
                 ->assertJson(['message' => 'The due date field must be a date after today.']);

        // Task 4: Send case number that is outside the range 100-10000
        $formData = [
            'title' => $task->title,
            'case_number' => 1e7,
            'due_date' => Carbon::now(),
            'timezone' => 'UTC'
        ];

        $response = $this->postJson('/api/tasks', $formData);

        $response->assertStatus(422)
                 ->assertJson(['message' => 'The case number field must be less than or equal to 10000.']);

    }

    /**
     * Test that tasks can be updated and that validation passes
     */
    public function test_update_task_validation(): void
    {
        $this->seed();

        /** @var Task */
        $task = Task::where('id', '5')->first();

        $this->assertEquals(1, $task->status);

        // Should fail: Set response status from Pending => Completed
        $response = $this->patchJson('/api/tasks/5', ['status' => 3]);
        $response->assertStatus(422);

        // Should pass: Set response status from Pending => In progress
        $response = $this->patchJson('/api/tasks/5', ['status' => 2]);
        $response->assertStatus(200);
        
        // Should fail: Set response status from In progress => Pending
        $response = $this->patchJson('/api/tasks/5', ['status' => 1]);
        $response->assertStatus(422);
                
        // Should pass: Set response status from In progress => Cancelled
        $response = $this->patchJson('/api/tasks/5', ['status' => 4]);
        $response->assertStatus(200);

        // Should fail: Set response status from Cancelled => Pending
        $response = $this->patchJson('/api/tasks/5', ['status' => 1]);
        $response->assertStatus(422);
        
        // Should fail: Set response status from Cancelled => Complete
        $response = $this->patchJson('/api/tasks/5', ['status' => 3]);
        $response->assertStatus(422);

        // Should pass: Set response status from Cancelled => In progress
        $response = $this->patchJson('/api/tasks/5', ['status' => 2]);
        $response->assertStatus(200);

        // Should pass: Set response status from In progress => Complete
        $response = $this->patchJson('/api/tasks/5', ['status' => 3]);
        $response->assertStatus(200);
    }

    /**
     * Test that tasks can be deleted
     */
    public function test_delete_task_validation(): void
    {
        $this->seed();

        /** @var Task */
        $task = Task::where('id', '5')->first();

        $this->assertEquals(5, $task->id);

        $response = $this->deleteJson('/api/tasks/5');
        $response->assertStatus(200);

        $task = Task::where('id', '5')->first();
        $this->assertEquals(null, $task);
    }
}
