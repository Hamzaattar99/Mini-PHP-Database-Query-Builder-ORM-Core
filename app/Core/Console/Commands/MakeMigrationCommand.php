<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Database\Migration\MigrationCreator;

class MakeMigrationCommand extends Command
{
    protected string $name =
        'make:migration';

    protected string $description =
        'Create migration file';

    protected array $arguments;

    public function __construct(
        array $arguments = []
    ) {
        $this->arguments =
            $arguments;
    }

    public function handle(): void
    {
        $name =
            $this->arguments[0]
            ?? null;

        if (!$name) {

            echo
                "Migration name required."
                . PHP_EOL;

            return;
        }

        $creator =
            new MigrationCreator();

        $file =
            $creator->create(
                $name
            );

        echo
            "Created: "
            . $file
            . PHP_EOL;
    }



    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }
}