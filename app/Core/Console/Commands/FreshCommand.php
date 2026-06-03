<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationManager;

class FreshCommand extends Command
{
    protected string $name =
        'fresh';

    protected string $description =
        'Drop all tables and re-run all migrations';

    public function handle(): void
    {
        $manager = new MigrationManager();

        $manager->fresh();

        echo "Database fresh started successfully." . PHP_EOL;
    }
}