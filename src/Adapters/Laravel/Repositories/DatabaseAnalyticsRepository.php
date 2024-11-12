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
     * @var array{
     *      max_analytics: int,
     *      allowed_analytics: array<int, string>,
     *      route_prefix: string,
     *      analytic_descriptions: array<string, string>,
     *  }
     */
    private array $config;

    /**
     * Creates a new analytics repository instance.
     */
    public function __construct(PanConfiguration $config)
    {
        $this->config = $config->toArray();
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
            clicks: (int) $analytic->clicks, // @phpstan-ignore-line,
            description: $this->config['analytic_descriptions'][$analytic->name] ?? null, // @phpstan-ignore-line
        ))->toArray();

        return $all;
    }

    /**
     * Increments the given event for the given analytic.
     */
    public function increment(string $name, EventType $event): void
    {
        [
            'allowed_analytics' => $allowedAnalytics,
            'max_analytics' => $maxAnalytics,
        ] = $this->config;

        if (count($allowedAnalytics) > 0 && ! in_array($name, $allowedAnalytics, true)) {
            return;
        }

        if (DB::table('pan_analytics')->where('name', $name)->count() === 0) {
            if (DB::table('pan_analytics')->count() < $maxAnalytics) {
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
