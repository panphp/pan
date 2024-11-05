<?php

declare(strict_types=1);

namespace Pan\Contracts;

use Pan\Enums\EventType;
use Pan\ValueObjects\Analytic;

/**
 * @internal
 */
interface AnalyticsRepository
{
    /**
     * Returns all analytics.
     *
     * @return array<int, Analytic>
     */
    public function all(): array;

    /**
     * Increments the given event for the given analytic.
     */
    public function increment(string $name, EventType $event): void;

    /**
     * Increments the given array of events for the given analytic.
     *
     * @param  array<array-key, EventType>  $events
     */
    public function incrementEach(string $name, array $events): void;

    /**
     * Flush all analytics.
     */
    public function flush(): void;
}
