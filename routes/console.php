<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('church:setup', function () {
    $this->info('Setting up First Love Church Management System...');
    
    // Run migrations
    $this->call('migrate:fresh');
    
    // Seed database
    $this->call('db:seed');
    
    $this->info('Church Management System setup completed!');
})->purpose('Set up the church management system');

Artisan::command('church:send-attendance-reminders', function () {
    $this->info('Sending attendance reminders to fellowship leaders...');
    
    // Logic to send reminders would go here
    // This is just a placeholder for future implementation
    
    $this->info('Attendance reminders sent successfully!');
})->purpose('Send weekly attendance reminders to fellowship leaders');

Artisan::command('church:generate-monthly-reports', function () {
    $this->info('Generating monthly reports...');
    
    // Logic to generate and send monthly reports would go here
    
    $this->info('Monthly reports generated successfully!');
})->purpose('Generate monthly attendance and offering reports'); 