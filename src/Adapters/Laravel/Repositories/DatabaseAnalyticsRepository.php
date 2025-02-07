<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Repositories;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
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
    public function __construct(
        private DatabaseManager $db,
        private PanConfiguration $config
    ) {}

    /**
     * Returns all analytics.
     *
     * @return array<int, Analytic>
     */
    public function all(): array
    {
        /** @var array<int, Analytic> $all */
        $all = $this->connection()->table('pan_analytics')->get()->map(fn (mixed $analytic): Analytic => new Analytic(
            id: (int) $analytic->id,
            name: $analytic->name,
            impressions: (int) $analytic->impressions,
            hovers: (int) $analytic->hovers,
            clicks: (int) $analytic->clicks,
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
        ] = $this->config->toArray();

        if (count($allowedAnalytics) > 0 && ! in_array($name, $allowedAnalytics, true)) {
            return;
        }

        if ($this->connection()->table('pan_analytics')->where('name', $name)->count() === 0) {
            if ($this->connection()->table('pan_analytics')->count() < $maxAnalytics) {
                $this->connection()->table('pan_analytics')->insert(['name' => $name, $event->column() => 1]);
            }

            return;
        }

        $this->connection()->table('pan_analytics')->where('name', $name)->increment($event->column());
    }

    /**
     * Flush all analytics.
     */
    public function flush(): void
    {
        $this->connection()->table('pan_analytics')->truncate();
    }

    /**
     * Resolve the database connection.
     */
    private function connection(): Connection
    {
        return $this->db->connection($this->config->getDatabaseConnection());
    }
}
