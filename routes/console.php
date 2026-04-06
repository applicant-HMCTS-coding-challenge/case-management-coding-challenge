<?php
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Defines a command to setup the database for the application: All migrations and seeders
|
*/
Artisan::command('setup', function () {

    $this->call('migrate');
    $this->call('db:seed');

})->purpose('Setup the application database: Run migrations and seeders');
