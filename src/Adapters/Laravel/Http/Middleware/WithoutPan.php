<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class WithoutPan
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
