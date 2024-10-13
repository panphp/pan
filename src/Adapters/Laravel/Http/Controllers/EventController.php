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

        $events->each(fn (array $event) => $action->handle($event['name'], EventType::from($event['type'])));

        return response()->noContent();
    }
}
