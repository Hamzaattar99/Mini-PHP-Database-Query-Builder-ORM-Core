<?php

namespace App\Core\Console;

class Kernel
{
    protected array $commands = [];

    public function register(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    public function run(string $commandName, array $arguments = []): void
    {
        if (!isset($this->commands[$commandName])) {
            echo "Command not found: {$commandName}" . PHP_EOL;
            return;
        }

            $command = $this->commands[$commandName];

        if (method_exists($command, 'setArguments')) {
            $command->setArguments($arguments);
        }

        $command->handle();
    }

    public function list(): void
    {
        foreach (
            $this->commands
            as $command
        ) {

            echo
                $command->getName()
                . " - "
                . $command->getDescription()
                . PHP_EOL;
        }
    }
}