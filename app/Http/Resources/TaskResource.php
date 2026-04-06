<?php

namespace App\Http\Resources;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Due Date converted to human readable format: Hour::Minute Day/Month/Year
     * 
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'case_number' => $this->case_number,
            'task_status' => Task::TASK_STATUS[$this->status],
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date . 'Z',
            'finalised_date' => $this->finalised_date ? $this->finalised_date . 'Z' : null,
            'overdue' => !$this->finalised_date && Carbon::createFromDate($this->due_date)->isPast()
        ]; 
    }
}
