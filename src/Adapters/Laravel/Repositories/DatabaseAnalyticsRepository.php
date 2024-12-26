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
        [
            'tenant_field' => $tenantField,
        ] = $this->config->toArray();

        /** @var array<int, Analytic> $all */
        $all = DB::table('pan_analytics')->get()->map(fn (mixed $analytic): Analytic => new Analytic(
            id: (int) $analytic->id,
            tenant: ($tenantField) ? $analytic->{$tenantField} : null,
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
            'tenant_field' => $tenantField,
            'tenant_id' => $tenantId,
        ] = $this->config->toArray();

        if (count($allowedAnalytics) > 0 && ! in_array($name, $allowedAnalytics, true)) {
            return;
        }

        // Restrict query to tenant if tenant field and id are set
        $baseQuery = DB::table('pan_analytics');

        if ($tenantField !== null && $tenantId !== null) {
            $baseQuery->where($tenantField, $tenantId);
        }

        $fieldQuery = clone $baseQuery;
        $fieldQuery = $fieldQuery->where('name', $name);

        if ($fieldQuery->count() === 0) {
            if ($baseQuery->count() < $maxAnalytics) {
                $baseQuery->insert(array_filter([
                    'name' => $name,
                    $event->column() => 1,
                    'tenant_field' => $tenantField,
                    'tenant_id' => $tenantId,
                ]));
            }

            return;
        }

        $fieldQuery->increment($event->column());
    }

    /**
     * Flush all analytics.
     */
    public function flush(): void
    {
        DB::table('pan_analytics')->truncate();
    }
}
