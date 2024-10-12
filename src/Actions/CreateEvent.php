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
     */
    public function handle(string $blueprint, string $event): void
    {
        [$name, $events] = $this->parse($blueprint);

        $event = EventType::tryFrom($event);

        if (! is_null($event) && (count($events) === 0 || in_array($event, $events))) {
            $this->repository->increment($name, $event);
        }
    }

    /**
     * Parses the given identifier.
     *
     * @return array{string, array<int, EventType>}
     */
    private function parse(string $blueprint): array
    {
        $blueprintAsArray = explode(':', trim($blueprint));

        $name = trim($blueprintAsArray[0]);

        $events = array_filter(array_map(
            fn ($event): ?\Pan\Enums\EventType => EventType::tryFrom(trim($event)),
            $name !== '' && $name !== '0' && count($blueprintAsArray) > 1 ? explode(',', $blueprintAsArray[1]) : [],
        ));

        return [$name, $events];
    }
}
