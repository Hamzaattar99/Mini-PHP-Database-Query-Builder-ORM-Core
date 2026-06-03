<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationManager;

class RollbackCommand extends Command
{
    protected string $name =
        'rollback';

    protected string $description =
        'Rollback migrations';

    protected array $arguments = [];

    public function setArguments(array $arguments): void {

        $this->arguments = $arguments;
    }

    public function handle(): void
    {
        $manager = new MigrationManager();

        $step = 0;

        foreach ($this->arguments as $argument) {

            if (str_starts_with($argument, '--step=')) {

                $step = (int) str_replace('--step=', '', $argument);
            }
        }

        if ($step > 0) {

            $manager->rollbackSteps($step);

            return;
        }

        $manager->rollback();
    }
}