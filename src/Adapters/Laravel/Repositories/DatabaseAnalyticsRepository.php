<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Repositories;

use Illuminate\Support\Facades\DB;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Pan\PanConfiguration;
use Pan\ValueObjects\Analytic;

/**
 * @internal
 */
final readonly class DatabaseAnalyticsRepository implements AnalyticsRepository
{
    /**
     * Creates a new analytics repository instance.
     */
    public function __construct(private PanConfiguration $config)
    {
        //
    }

    /**
     * Returns all analytics.
     *
     * @return array<int, Analytic>
     */
    public function all(): array
    {
        /** @var array<int, Analytic> $all */
        $all = DB::table('pan_analytics')->get()->map(fn (mixed $analytic): Analytic => new Analytic(
            id: (int) $analytic->id, // @phpstan-ignore-line
            name: $analytic->name, // @phpstan-ignore-line
            impressions: (int) $analytic->impressions, // @phpstan-ignore-line
            hovers: (int) $analytic->hovers, // @phpstan-ignore-line
            clicks: (int) $analytic->clicks, // @phpstan-ignore-line
        ))->toArray();

        return $all;
    }

    /**
     * Increments the given event for the given analytic.
     */
    public function increment(string $name, EventType $event): void
    {
        if ($this->isNotAllowed($name)) {
            return;
        }

        if ($this->isNewAnalytic($name)) {
            if ($this->canAddMoreAnalytics()) {
                DB::table('pan_analytics')->insert(['name' => $name, $event->column() => 1]);
            }

            return;
        }

        DB::table('pan_analytics')->where('name', $name)->increment($event->column());
    }

    /**
     * Increments the given array of events for the given analytic.
     *
     * @param  array<array-key, EventType>  $events
     */
    public function incrementEach(string $name, array $events): void
    {
        if ($this->isNotAllowed($name)) {
            return;
        }

        if ($this->isNewAnalytic($name)) {
            if ($this->canAddMoreAnalytics()) {
                $data = array_merge(['name' => $name], array_fill_keys(array_map(fn (EventType $event): string => $event->column(), $events), 1));
                DB::table('pan_analytics')->insert($data);
            }

            return;
        }

        DB::table('pan_analytics')->where('name', $name)->incrementEach(array_fill_keys(array_map(fn (EventType $event): string => $event->column(), $events), 1));
    }

    /**
     * Flush all analytics.
     */
    public function flush(): void
    {
        DB::table('pan_analytics')->truncate();
    }

    /**
     * Check if the analytic is not allowed.
     */
    private function isNotAllowed(string $name): bool
    {
        ['allowed_analytics' => $allowedAnalytics] = $this->config->toArray();

        return count($allowedAnalytics) > 0 && ! in_array($name, $allowedAnalytics, true);
    }

    /**
     * Check if the analytic is new.
     */
    private function isNewAnalytic(string $name): bool
    {
        return DB::table('pan_analytics')->where('name', $name)->count() === 0;
    }

    /**
     * Check if we can add more analytics.
     */
    private function canAddMoreAnalytics(): bool
    {
        ['max_analytics' => $maxAnalytics] = $this->config->toArray();

        return DB::table('pan_analytics')->count() < $maxAnalytics;
    }
}
