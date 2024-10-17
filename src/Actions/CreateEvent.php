<?php

declare(strict_types=1);

namespace Pan\Actions;

use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;

/**
 * @internal
 */
final readonly class CreateEvent
{
    /**
     * Creates a new action instance.
     */
    public function __construct(
        private AnalyticsRepository $repository,
    ) {
        //
    }

    /**
     * Executes the action.
     *
     * @param  EventType|array<array-key,EventType>  $event
     */
    public function handle(string $name, EventType|array $event): void
    {
        if (is_array($event)) {
            $this->repository->incrementEach($name, $event);
        } else {
            $this->repository->increment($name, $event);
        }
    }
}
