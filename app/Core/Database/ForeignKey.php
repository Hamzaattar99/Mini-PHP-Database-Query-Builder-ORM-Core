<?php

namespace App\Core\Database;

class ForeignKey
{
    protected string $column;

    protected string $referenceColumn;

    protected string $referenceTable;

    protected ?string $onDelete = null;

    protected ?string $onUpdate = null;

    protected ?string $constraintName = null;

    public function __construct(string $column)
    {
        $this->column = $column;
    }

    public function references(string $column): self
    {
        $this->referenceColumn = $column;

        return $this;
    }

    public function on(string $table): self
    {
        $this->referenceTable = $table;

        return $this;
    }

    public function onDelete(string $action): self
    {
        $this->onDelete = strtoupper($action);

        return $this;
    }

    public function onUpdate(string $action): self
    {
        $this->onUpdate = strtoupper($action);

        return $this;
    }

    public function toSql(): string
    {

        if($this->constraintName)
            {
                $sql = "CONSTRAINT {$this->constraintName} ".
                        "FOREIGN KEY ({$this->column}) ".
                        "REFERENCES {$this->referenceTable}({$this->referenceColumn})";
            }
        else
            {
                $sql = "FOREIGN KEY ({$this->column}) ".
                       "REFERENCES {$this->referenceTable}({$this->referenceColumn})";
            }
        

        if ($this->onDelete) {
            $sql .= " ON DELETE {$this->onDelete}";
        }

        if ($this->onUpdate) {
            $sql .= " ON UPDATE {$this->onUpdate}";
        }

        return $sql;
    }


    public function toAlterSql(): string
    {
       if($this->constraintName)
            {
                $sql = "ADD CONSTRAINT {$this->constraintName} ".
                        "FOREIGN KEY ({$this->column}) ".
                        "REFERENCES {$this->referenceTable}({$this->referenceColumn})";
            }
        else
            {
                $this->constraintName = $this->getConstraintName();

                $sql = "ADD CONSTRAINT {$this->constraintName} ".
                        "FOREIGN KEY ({$this->column}) ".
                        "REFERENCES {$this->referenceTable}({$this->referenceColumn})";
            }

        if ($this->onDelete) {
            $sql .= " ON DELETE {$this->onDelete}";
        }

        if ($this->onUpdate) {
            $sql .= " ON UPDATE {$this->onUpdate}";
        }

        return $sql;
    }
    // to get a user-specified constraint name 
    public function name(string $name): self
    {
        $this->constraintName = $name;

        return $this;
    }
    // to get a defualt constraint name 
    public function getConstraintName(): string
    {
        return $this->constraintName ?? "{$this->referenceTable}_{$this->column}_foreign";
    }

}