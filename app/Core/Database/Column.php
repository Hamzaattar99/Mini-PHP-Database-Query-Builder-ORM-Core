<?php
namespace App\Core\Database;

class Column
{
    protected string $definition;

    protected bool $nullable = false;
    protected bool $primary = false;
    protected bool $autoIncrement = false;

    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    public function nullable(): self
    {
        $this->nullable = true;
        return $this;
    }

    public function primary(): self
    {
        $this->primary = true;
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function toSql(): string
    {
        $sql = $this->definition;

        if (!$this->nullable) {
            $sql .= ' NOT NULL';
        }

        if ($this->autoIncrement) {
            $sql .= ' AUTO_INCREMENT';
        }

        if ($this->primary) {
            $sql .= ' PRIMARY KEY';
        }

        return $sql;
    }
}


?>