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
            'id' => '<fg=gray>#'.$analytic->id.'</>',
            'name' => '<fg=gray>'.$analytic->name.'</>',
            'impressions' => $this->toHumanReadableNumber($analytic->impressions),
            'hovers' => $this->toHumanReadableNumber($analytic->hovers).' ('.$this->toHumanReadablePercentage($analytic->impressions, $analytic->hovers).')',
            'clicks' => $this->toHumanReadableNumber($analytic->clicks).' ('.$this->toHumanReadablePercentage($analytic->impressions, $analytic->clicks).')',
        ];
    }

    /**
     * Returns a human-readable number.
     */
    private function toHumanReadableNumber(int $number): string
    {
        return number_format($number);
    }

    /**
     * Returns a human-readable percentage.
     */
    private function toHumanReadablePercentage(int $total, int $part): string
    {
        if ($total === 0) {
            return 'Infinity%';
        }

        return number_format($part / $total * 100, 1).'%';
    }
}
