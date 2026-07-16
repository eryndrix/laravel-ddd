<?php

return [

	'register' => [
		'success' => 'Registration successful!',
		'failed' => 'Registration failed. Please try again.',
		'unknown' => 'An error occurred during registration.',
	],

	'login' => [
	    'invalid_credentials' => 'Invalid email or password.',
	    'unauthorized' => 'Client failed to authorize.',
	    'failed' => 'Login failed. Please try again.',
	    'unknown' => 'An error occurred during login.',
	],

	'refresh_token' => [
		'invalid_format' => 'Invalid refresh token format.',
		'not_exists' => 'Refresh token does not exist.',
		'revoked' => 'Refresh token has been revoked (already used).',
		'expired' => 'Refresh token has expired.',
		'missing_ability' => 'Token missing required ability to refresh.',
		'failed' => 'Token refresh failed. Please try again.',
		'unknown' => 'An error occurred during token refresh.',
	],

	'logout' => [
		'success' => 'Logout successful!',
		'unauthorized' => 'You are not authorized to log out.',
		'failed' => 'Logout failed. Please try again.',
		'unknown' => 'An error occurred during logout.',
	],

	'password' => [
		'forgot' => [
			'success' => 'Password reset link sent to your email!',
			'too_many_attempts' => 'Too many password reset requests. Please wait.',
			'invalid_email_format' => 'Invalid email format.',
			'email_not_exists' => 'No account found with this email.',
			'failed' => 'Failed to send password reset link. Please try again.',
			'unknown' => 'An error occurred during password reset request.',
		],
		'reset' => [
            'success' => 'Password reset successfully!',
            'invalid_email' => 'User not found or invalid token.',
            'invalid_format' => 'Password validation failed.',
            'failed' => 'Failed to reset password. Please try again.',
            'unknown' => 'An error occurred during password reset.',
        ],
        'update' => [
            'success' => 'Password update successfully!',
            'mismatch' => 'Password and password confirmation do not match.',
            'invalid_pwd_format' => 'Password must be 8-25 characters.',
            'failed' => 'Failed to update password. Please try again.',
            'unknown' => 'An error occurred during password update.',
        ],
	],

    'email' => [
        'update' => [
            'success' => 'Verification sent to your new email!',
            'invalid_format' => 'Invalid email format.',
            'same_as_current' => 'Email is the same as your current email.',
            'failed' => 'Failed to update email. Please try again.',
            'unknown' => 'An error occurred during email update.',
        ],
        'verify' => [
            'success' => 'Email confirmed successfully!',
            'invalid_hash' => 'Invalid or expired token. Please log in and try again.',
            'already_completed' => 'Email confirmation already completed.',
            'failed' => 'Email verification failed. Please log in and try again.',
            'unknown' => 'An error occurred during email verification.',
        ],
    ],

    'user' => [
	    'failed' => 'User operation failed. Please try again.',
	    'unknown' => 'An error occurred during user operation.',
	],

];
