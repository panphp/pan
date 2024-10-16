<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Pan\Adapters\Laravel\PanManager;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Pan\ValueObjects\Analytic;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 */
final readonly class DatabaseAnalyticsRepository implements AnalyticsRepository
{
    /**
     * Returns all analytics.
     *
     * @return array<int, Analytic>
     */
    public function all(): array
    {
        /** @var array<int, Analytic> $all */
        $all = DB::table('pan_analytics')->get()->map(fn (mixed $analytic): Analytic => new Analytic(
            id: $analytic->id, // @phpstan-ignore-line
            name: $analytic->name, // @phpstan-ignore-line
            impressions: $analytic->impressions, // @phpstan-ignore-line
            hovers: $analytic->hovers, // @phpstan-ignore-line
            clicks: $analytic->clicks, // @phpstan-ignore-line
        ))->toArray();

        return $all;
    }

    /**
     * Increments the given event for the given analytic.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function increment(string $name, EventType $event): void
    {
        $query = DB::table('pan_analytics')->get();

        /** @var PanManager $manager */
        $manager = Container::getInstance()->get('pan');

        $allowed = $manager->allowed();

        /** @phpstan-ignore argument.type */
        if (count($allowed) > 0 && ! in_array($name, $allowed)) {
            return;
        }

        if ($query->where('name', $name)->count() === 0) {
            if ($query->count() < $manager->max()) {
                DB::table('pan_analytics')->insert(['name' => $name, $event->column() => 1]);
            }

            return;
        }

        DB::table('pan_analytics')->where('name', $name)->increment($event->column());
    }

    /**
     * Flush all analytics.
     */
    public function flush(): void
    {
        DB::table('pan_analytics')->truncate();
    }
}
