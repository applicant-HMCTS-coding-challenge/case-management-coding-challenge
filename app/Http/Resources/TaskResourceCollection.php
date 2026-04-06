<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     * 
     * Groups the list of tasks by each task's status
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [

            // This includes every task assigned, even completed and cancelled ones. Could be changed to only include incomplete still active tasks.
            'assignedTasks' => count($this->collection),
            'tasks' => []
        ];

        foreach ($this->collection as $task)
        {
            $response['tasks'][Task::TASK_STATUS[$task->status]][] = $task;
        }

        return $response;
    }
}
