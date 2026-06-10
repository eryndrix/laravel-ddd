<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'invalid_credentials' => 'Invalid email or password.',

    'registration' => [
        'success' => 'Registration successful!',
        'failed' => 'Registration failed. Please try again later.',
    ],

    'login' => [
        'too_many_attempts' => 'Too many login attempts. Please wait a few minutes.',
        'failed' => 'Login failed. Please try again later.',
    ],

    'logout' => [
        'success' => 'Logout successful!',
        'failed' => 'Logout failed. Please try again later.',
    ],

    'token' => [
        'invalid' => 'Invalid refresh token.',
        'expired' => 'Refresh token has expired. Please log in again.',
        'missing_ability' => 'This token cannot be used to refresh.',
    ],
];
