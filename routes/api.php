<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes for each API endpoint detailed in TaskController
*/
Route::resource('tasks', TaskController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
?>