<?php declare(strict_types=1);

namespace App\Shared\Presentation;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Action extends Controller
{
    /**
     * Handles authorization checks and policies.
     */
    use AuthorizesRequests;

    /**
     * Manages request validation and errors.
     */
    use ValidatesRequests;
}
