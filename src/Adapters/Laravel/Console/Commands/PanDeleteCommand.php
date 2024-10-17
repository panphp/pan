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

        if ($this->isInvalidId($id)) {
            $this->error('Analytic ID must be greater than 0.');

            return;
        }

        if ($repository->delete($id) !== 0) {
            $this->info('Analytic has been deleted.');
        } else {
            $this->error('Record not found or already deleted.');
        }
    }

    /**
     * Check if the ID is invalid.
     */
    private function isInvalidId(int $id): bool
    {
        return $id <= 0;
    }
}
