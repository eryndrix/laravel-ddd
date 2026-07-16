<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password/reset', function () {
    return view('password.reset');
});

Route::get(
    '/email/verify',
    \App\Identity\Presentation\Email\Verify\VerifyEmailAction::class
)->middleware(
    ['signed', 'throttle:6,1']
)->name(
    'verification.verify'
);
