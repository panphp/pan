<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Pan\Console\Table;
use Pan\Contracts\AnalyticsRepository;
use Pan\ValueObjects\Analytic;

/**
 * @internal
 */
final class PanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all your analytics';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsRepository $analytics): void
    {
        $analytics = $analytics->all();

        if ($analytics === []) {
            $this->components->info('No analytics have been recorded yet. Get started by adding the [data-pan] attribute to your HTML elements.');

            return;
        }

        (new Table($this->output))->display(
            ['', 'Name', 'Impressions', 'Hovers', 'Clicks'],
            array_map(fn (Analytic $analytic): array => [
                '#'.$analytic->id,
                $analytic->name,
                (string) $analytic->impressions,
                $analytic->hovers.' ('.$this->toHumanReadablePercentage($analytic->hovers / $analytic->impressions * 100).')',
                $analytic->clicks.' ('.$this->toHumanReadablePercentage($analytic->clicks / $analytic->impressions * 100).')',
            ], $analytics)
        );
    }

    /**
     * Returns a human-readable percentage.
     */
    private function toHumanReadablePercentage(float $percentage): string
    {
        return number_format($percentage, 1).'%';
    }
}
