<?php

declare(strict_types=1);

namespace Pan\Presentors;

use Pan\ValueObjects\Analytic;

/**
 * @internal
 */
final class AnalyticPresentor
{
    /**
     * Returns the human-readable information about the analytic.
     *
     * @return array<string, string>
     */
    public function present(Analytic $analytic): array
    {
        return [
            'id' => '#'.$analytic->id,
            'name' => $analytic->name,
            'impressions' => (string) $analytic->impressions,
            'hovers' => $analytic->hovers.' ('.$this->toHumanReadablePercentage($analytic->hovers / $analytic->impressions * 100).')',
            'clicks' => $analytic->clicks.' ('.$this->toHumanReadablePercentage($analytic->clicks / $analytic->impressions * 100).')',
        ];
    }

    /**
     * Returns a human-readable percentage.
     */
    private function toHumanReadablePercentage(float $percentage): string
    {
        return number_format($percentage, 1).'%';
    }
}
