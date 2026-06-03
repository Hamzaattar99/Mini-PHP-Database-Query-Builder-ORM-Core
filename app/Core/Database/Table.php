<?php

namespace App\Core\Database;

use App\Core\Database\Exceptions\DatabaseException;


class Table
{
    protected Blueprint $blueprint;

    protected array $allowedTypes = [
        'varchar',
        'char',
        'text',
        'int',
        'bigint',
        'smallint',
        'tinyint',
        'boolean',
        'float',
        'double',
        'decimal',
        'date',
        'datetime',
        'timestamp',
        'json',
    ];

    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    public function column(string $name, string $type, $length = null): Column
    {
         $type = strtolower($type);

          if (!in_array($type, $this->allowedTypes)) {
            throw new DatabaseException("Invalid column type: {$type}");
        }

        $sqlType = $this->buildType($type, $length);

        $column = new Column("{$name} {$sqlType}");

        if ($this->blueprint->getMode() === 'create') {

                $this->blueprint->addColumn($column);

            } else {

                $this->blueprint->addCommand(
                    'ADD COLUMN ' . $column->toSql()
                );

}

        return $column;
    }

    protected function buildType(string $type, int|null $length)
    {
        return match ($type) {
            'varchar', 'char' => $length ? strtoupper($type) . "({$length})" : strtoupper($type) . "(255)",
           'decimal' => $length ? "DECIMAL({$length})" : "DECIMAL(10,2)",
            default => strtoupper($type),
        };
    }

     public function timestamps()
    {
        $this->blueprint->addColumn("created_at TIMESTAMP NULL");
        $this->blueprint->addColumn("updated_at TIMESTAMP NULL");
    }

    public function dropColumn(string $column): void
    {
        $this->blueprint->addCommand(
            "DROP COLUMN {$column}"
        );
    }
    
    public function dropColumns(array $columns): void
    {
        foreach ($columns as $column) {
            $this->dropColumn($column);
        }
    }
}

?>