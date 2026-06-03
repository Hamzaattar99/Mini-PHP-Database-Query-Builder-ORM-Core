<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationManager;

class ResetCommand extends Command
{
    protected string $name =
        'reset';

    protected string $description =
        'Rollback all migrations';

    public function handle(): void
    {
        $manager = new MigrationManager();

        $manager->reset();
    }
}