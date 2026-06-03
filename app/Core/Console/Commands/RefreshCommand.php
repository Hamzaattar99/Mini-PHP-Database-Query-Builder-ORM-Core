<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationManager;

class RefreshCommand extends Command
{
    protected string $name =
        'refresh';

    protected string $description =
        'Reset and re-run all migrations';

    public function handle(): void
    {
        $manager = new MigrationManager();

        $manager->refresh();

        echo "Database refreshed successfully." . PHP_EOL;
    }
}