<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class TaskStatus implements ValidationRule
{
    /**
     * The current status of the task
     * 
     * @var int
     */
    private int $currentStatus;

    public function __construct(int $currentStatus)
    {
        $this->currentStatus = $currentStatus;
    }

    /**
     * Run the validation rule.
     *
     * Validation Rules:
     * Pending tasks can be updated to In Progress or Cancelled
     * In Progress tasks can be updated to Completed or Cancelled
     * Completed or Cancelled tasks can be set back to In Progress
     * 
     * Justifications:
     * Pending tasks shouldn't be able to be marked Completed until they have been started and worked through.
     * In Progress tasks shouldn't be able to be marked Pending as they have been started.
     * A task that has been completed or cancelled may be re-open if necessary
     * 
     * @see App/Models/Task
     * For description of Task Statuses
     * 1 -> Pending
     * 2 -> In Progress
     * 3 -> Completed
     * 4 -> Cancelled
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $statusValid = true;
        $message = '';

        if ($this->currentStatus == 1 && ($value != 2 && $value != 4))
        {
            $statusValid = false;
            $message = 'Pending tasks can only be set to In Progress or Cancelled';
        }
        elseif ($this->currentStatus == 2 && ($value != 3 && $value != 4))
        {
            $statusValid = false;
            $message = 'In Progress tasks can only be set to Completed or Cancelled';
        }
        elseif ($this->currentStatus > 2 && ($value !== 2))
        {
            $statusValid = false;
            $message = 'Completed or Cancelled tasks can only be set to In progress if re-opened.';
        }

        if (!$statusValid)
        {
            $fail($message);
        }
    }
}
