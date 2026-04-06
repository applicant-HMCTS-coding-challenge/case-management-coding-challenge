<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes all except api calls to the frontend React app which handles Routing
*/
Route::view('{any}', 'app')->where('any', '^(?!api).*$')->name('React App'); 
?>