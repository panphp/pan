<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Pan\Console\Table;
use Pan\Contracts\AnalyticsRepository;
use Pan\Presentors\AnalyticPresentor;
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
    protected $signature = 'pan {--filter= : Filter the analytics by name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all your analytics';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsRepository $analytics, AnalyticPresentor $presentor): void
    {
        $analytics = $analytics->all();

        if (is_string($filter = $this->option('filter'))) {
            $analytics = array_filter($analytics, fn (Analytic $analytic): bool => str_contains($analytic->name, $filter));
        }

        if ($analytics === []) {
            $this->components->info('No analytics have been recorded yet. Get started collecting analytics by adding the [data-pan="my-button"] attribute to your HTML elements.');

            return;
        }

        (new Table($this->output))->display(
            ['', 'Name', 'Description', 'Impressions', 'Hovers', 'Clicks'],
            array_map(fn (Analytic $analytic): array => array_values($presentor->present($analytic)), $analytics)
        );
    }
}
