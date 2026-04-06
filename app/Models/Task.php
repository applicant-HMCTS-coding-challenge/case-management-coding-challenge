<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	use HasFactory;
	
	/** Task Statuses defined using resource: https://crmone.com/glossary/task-status */
	public const TASK_STATUS = [
		
		/**
		 * Pending Task
		 * 
		 * The task has been assigned but not yet actioned
		 */
		1 => "Pending",
		
		/**
		 * In Progress Task
		 * 
		 * The task has been started, and not yet completed or cancelled
		 */
		2 => "In Progress",
		
		/**
		 * Completed Task
		 * 
		 * The task has been completed
		 */
		3 => "Completed",

		/**
		 * Cancelled Task
		 * 
		 * The task has been cancelled
		 */
		4 => "Cancelled"
	];

	/**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 1
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'case_number', 'due_date'];
}
