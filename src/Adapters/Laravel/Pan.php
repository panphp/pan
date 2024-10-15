<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel;

use Illuminate\Support\Facades\Facade;

final class Pan extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'pan';
    }
}
