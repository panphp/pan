<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Console\Commands;

use Illuminate\Console\Command;

use function Termwind\render;

/**
 * @internal
 */
final class InstallPanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:pan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Pan package';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $existingMigrations = glob(database_path('migrations/*_create_pan_tables.php'));

        if ($existingMigrations === []) {
            $this->output->writeln('');

            $this->components->task('Publishing Pan migrations', function (): void {
                $this->callSilent('vendor:publish', ['--tag' => 'pan-migrations']);
            });

            if ($this->components->confirm('Would like to run the migrations now?')) {
                $this->call('migrate');
            }
        }

        render(<<<'HTML'
            <code line="2">
                <div>
                    <button data-pan="my-button">
                        Your Button
                    </button>
                </div>
            </code>
            HTML,
        );

        $this->components->info('Pan was installed successfully. You may start collecting analytics by adding the [data-pan="my-button"] attribute to your HTML elements. You can view analytics by running the [artisan pan] command.');
    }
}
