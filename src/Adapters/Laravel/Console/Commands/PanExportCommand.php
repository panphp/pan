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

        if ($data === []) {
            $this->components->info('There are no analytics to export.');

            return;
        }

        $fileName = 'analytics_export_'.now()->format('Y-m-d_H-i-s').'.csv';
        $dir = storage_path('app/pan');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
            $this->components->info('The directory storage/app/pan has been created.');
        }

        if (! is_dir($dir)) {
            $this->components->error('The directory storage/app/pan does not exist and could not be created.');

            return;
        }

        if (! is_writable($dir)) {
            $this->components->error('The directory storage/app/pan is not writable.');

            return;
        }

        $handle = fopen($dir.'/'.$fileName, 'w');

        if ($handle === false) {
            $this->components->error('The file storage/app/pan/'.$fileName.' could not be created.');

            return;
        }

        fputcsv($handle, ['ID', 'Name', 'Impressions', 'Hovers', 'Clicks']);

        foreach ($data as $row) {
            fputcsv($handle, [$row->id, $row->name, $row->impressions, $row->hovers, $row->clicks]);
        }

        fclose($handle);

        $this->components->info('All analytics have been exported to storage/app/pan/'.$fileName);
    }
}
