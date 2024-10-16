<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Controllers;

use Illuminate\Cache\RateLimiter;
use Illuminate\Container\Container;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Pan\Actions\CreateEvent;
use Pan\Adapters\Laravel\Http\Requests\CreateEventRequest;
use Pan\Adapters\Laravel\PanManager;
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
        /** @var RateLimiter $limiter */
        $limiter = Container::getInstance()->get(RateLimiter::class);

        /** @var PanManager $manager */
        $manager = Container::getInstance()->get('pan');

        $limiter->attempt(
            'pan:'.$request->ip(),
            /** @phpstan-ignore argument.type */
            maxAttempts: $manager->perMinute(),
            callback: function () use ($request, $action): void {
                /** @var Collection<int, array{name: string, type: string}> $events */
                $events = $request->collect('events');

                $events->each(
                    fn (array $event) => $action->handle($event['name'], EventType::from($event['type']))
                );
            }
        );

        return response()->noContent();
    }
}
