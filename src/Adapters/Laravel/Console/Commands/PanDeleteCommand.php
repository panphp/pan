<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Pan\Contracts\AnalyticsRepository;

class PanDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pan:delete {id : The ID of the analytic to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete analytics by ID.';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsRepository $repository): void
    {
        $id = (int) $this->argument('id');

        if ($id > 0) {
            $repository->delete($id);
            $this->info('Analytic has been deleted.');
        }

        $this->error('The ID must be a positive integer.');
    }
}
