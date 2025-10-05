<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Mail\ForgotPassword;

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

Artisan::command('test-email', function () {
    $data = [
        'title' => 'Forgot Password',
        'body' => 'This is a test email sent using Mailgun and Laravel.'
    ];

    Mail::to('ericsonpaciente23@gmail.com')->send(new ForgotPassword($data));

    echo 'Email Sent!';
});