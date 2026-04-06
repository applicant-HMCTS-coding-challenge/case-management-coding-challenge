<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskResourceCollection;
use App\Models\Task;
use App\Rules\TaskStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Controller for all Task related API endpoints
 * 
 * Future Considerations
 * As this application has no user authentication, anyone can view, update or delete
 * every task within the database. 
 * 
 * If User authentication was to be implemented, tasks could be assigned to users 
 * and requests validated to ensure the user has permission to perform operations on the task.
 * 
 * For Example:
 * Manager assigns task to User
 * User can view, can update to specific statuses, can't cancel or delete a task
 */
class TaskController extends Controller
{
    /**
     * Retrieve all Tasks
     * 
     * Returns list of all current (undeleted) tasks
     * 
     * @return 
     * HTTP 200 Ok - TaskResourceCollection
     * Tasks are grouped in the response by each task's current status
     */
    public function index()
    {
        return new TaskResourceCollection(Task::orderBy("status")->orderBy("due_date")->get());
    }

    /**
     * Store Task
     * 
     * Creates a new task given the following attributes:
     * 
     * Required:
     * Title - 30 characters max
     * Case Number - Number between 100 and 10000
     * Due Date - Must be a date in the future after Today
     * 
     * All dates are stored by the application as UTC
     * 
     * Optional:
     * Description - Text to describe what the purpose of the task is
     * 
     * All fields validated against CSRF and SQL Injection by Laravel's routing middlewhere
     * and the query builder. Any XSS vulnerabilies such as code being passed through in the
     * description field should be mitigated against in the frontend application
     * 
     * Future considerations
     * Case Number is currently random, could be iterative or linked to an external library
     * to give the number more meaning
     * 
     * @return
     * HTTP 200 Ok - ID of the new created task
     * HTTP 422 Unprocessable Entity - Validation failed on one or more fields, errors returned in JSON array
     */
    public function store(Request $request)
    {
        $validatedInput = $request->validate([
            'title' => ['required', 'max:30'],
            'case_number' => ['required', 'gte:100', 'lte:10000'],
            'due_date' => ['required', Rule::date()->afterToday()],
            'timezone' => ['required', 'string', 'max:50'],
            'description' => ['nullable']
        ]);

        $task = new Task($validatedInput);

        $task->due_date = Carbon::parse(
            $validatedInput['due_date'], 
            $validatedInput['timezone']
        )->clone()->timezone('UTC');

        $task->save();

        return ["data" => ["id" => $task->id]];
    }

    /**
     * Retrieve a Task
     * 
     * @return
     * HTTP 200 Ok - TaskResource
     * 
     * Containing the following fields
     * The task Id
     * The task case number
     * The task's current status
     * The title of the task
     * The optional description for the task
     * The due date for the task in a human readable format
     * The number of days until the task is due (0 if the task is due in under 24 hours)
     */
    public function show($id)
    {
        return new TaskResource(Task::where(['id' => $id])->firstOrFail());
    }

    /**
     * Update a Task
     * 
     * Updates the status of a task given the task ID and new status
     * 
     * Validation Rules:
     * Pending tasks can be updated to In Progress or Cancelled
     * In Progress tasks can be updated to Completed or Cancelled
     * 
     * When a task is set to Completed or Cancelled, the finalised date
     * for the task will be set to the current date and time. If the
     * task is reopened, this date will be removed.
     * 
     * @return
     * HTTP 200 Ok - Update successful
     * HTTP 422 Unprocessable Entity - The provided new status number is invalid
     */
    public function update($id, Request $request)
    {
        /** @var Task */
        $task = Task::where(['id' => $id])->firstOrFail();

        $validatedInput = $request->validate([
            'status' => ['required', 'integer', new TaskStatus($task->status)]
        ]);

        // Set the completed / cancelled date to now
        if ($validatedInput['status'] > 2)
            $task->finalised_date = Carbon::now();
        else
            $task->finalised_date = null;

        $task->status = $validatedInput['status'];
        $task->save();
    }

    /**
     * Destroy Task
     * 
     * Deletes a Task from the database given the task ID
     * 
     * @return
     * HTTP 200 Ok - Task deleted successfully
     * HTTP 422 Unprocessable Entity - The task could not be deleted, as doesn't exist, or the ID is invalid etc.
     */
    public function destroy($id)
    {
        /** @var Task */
        $task = Task::where(['id' => $id])->firstOrFail();

        $task->delete();
    }
}
