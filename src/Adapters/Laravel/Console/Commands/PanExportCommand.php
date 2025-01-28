<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Pan\Contracts\AnalyticsRepository;

/**
 * @internal
 */
final class PanExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pan:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all your analytics';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsRepository $analytics): void
    {
        $data = $analytics->export();
        $fileName = now()->format('Y-m-d_H-i-s').'.csv';

        $dir = storage_path('app/pan');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $handle = fopen($dir.'/'.$fileName, 'w');

        fputcsv($handle, ['', 'Name', 'Impressions', 'Hovers', 'Clicks']);

        foreach($data as $row) {
            fputcsv($handle, array_values($row->toArray()));
        }

        fclose($handle);

        $this->components->info('All analytics have been exported to storage/app/pan/' . $fileName);
    }
}
