<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Pan\Contracts\AnalyticsRepository;

/**
 * @internal
 */
final class PanFlushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pan:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush all your analytics';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsRepository $analytics): void
    {
        $analytics->flush();

        $this->components->info('All analytics have been flushed.');
    }
}
