<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationManager;

class MigrateCommand extends Command
{
    protected string $name = 'migrate';

    protected string $description =
        'Run pending migrations';

    public function handle(): void
    {
        $manager =
            new MigrationManager();

        $manager->migrate();
    }
}