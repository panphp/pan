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
     * Flush all analytics.
     */
    public function flush(): void;

    /**
     * Delete a specific analytic by ID.
     */
    public function delete(int $id): void;
}
