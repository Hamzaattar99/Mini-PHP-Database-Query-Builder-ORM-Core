<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationManager;

class StatusCommand extends Command
{
    protected string $name =
        'status';

    protected string $description =
        'Show migration status';

    public function handle(): void
    {
        $manager =
            new MigrationManager();

        $status =
            $manager->getStatus();

        echo PHP_EOL;

        echo str_pad(
            'Ran',
            10
        );

        echo str_pad(
            'Migration',
            50
        );

        echo PHP_EOL;

        echo str_repeat('-', 60);

        echo PHP_EOL;

        foreach ($status as $row) {

            echo str_pad($row['ran'] ? 'Yes' : 'No', 10);

            echo str_pad($row['migration'], 50);


            echo PHP_EOL;
        }

        echo PHP_EOL;
        echo str_repeat('-', 60);
        echo PHP_EOL;

        if ($manager->hasPending()) {
        echo "Pending migrations exist";
        }

        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
    }
}