<?php

declare(strict_types=1);

namespace Pan\Console;

use Symfony\Component\Console\Helper\Table as BaseTable;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final readonly class Table
{
    /**
     * Creates a new instance of the table.
     */
    public function __construct(
        private OutputInterface $output,
    ) {
        //
    }

    /**
     * Displays the table.
     *
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, string>>  $rows
     */
    public function display(array $headers, array $rows): void
    {
        $this->output->writeln('');

        $table = new BaseTable($this->output);

        $table->setStyle('compact');

        $table->setHeaders(array_map(
            fn ($header): string => "   <fg=DeepPink;options=bold>$header</>",
            $headers
        ));

        $table->setRows(array_map(
            fn ($row): array => array_map(
                fn ($cell): string => "   <options=bold>$cell</>",
                $row
            ),
            $rows
        ));

        $table->render();

        $this->output->writeln('');
    }
}
