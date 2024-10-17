<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Pan\Actions\CreateEvent;
use Pan\Adapters\Laravel\Http\Requests\CreateEventRequest;
use Pan\Enums\EventType;

/**
 * @internal
 */
final readonly class EventController
{
    /**
     * Store a new event.
     */
    public function store(CreateEventRequest $request, CreateEvent $action): Response
    {
        /** @var Collection<int, array{name: string, type: string}> $events */
        $events = $request->collect('events');

        $events->groupBy(fn (array $event): string => $event['name'])
            ->each(function (Collection $eventsByName, string $name) use ($action): void {
                if ($eventsByName->count() > 1) {
                    $action->handle($name, $eventsByName->flatMap(fn (array $event): array => [EventType::from($event['type'])])->all());
                } else {
                    $action->handle($name, EventType::from($eventsByName->sole()['type']));
                }
            });

        return response()->noContent();
    }
}
