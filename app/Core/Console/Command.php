<?php

namespace App\Core\Console;

abstract class Command
{
    /**
     * Command name.
     */
    protected string $name;

    /**
     * Description.
     */
    protected string $description;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    abstract public function handle(): void;
}