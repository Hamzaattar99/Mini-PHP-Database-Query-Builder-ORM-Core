<?php


namespace App\Core\Database;



class Blueprint
{
    protected string $table;
    protected array $columns = [];
    protected string $mode = 'create';
    protected array $commands = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    // public function string(string $name, int $length = 255): Column
    // {
    //     $column = new Column("{$name} VARCHAR({$length})");
    //     $this->columns[] = $column;
    //     return $column;
    // }

    // public function integer(string $name): Column
    // {
    //     $column = new Column("{$name} INT");
    //     $this->columns[] = $column;
    //     return $column;
    // }

    public function addColumn(Column|string $sql)
    {
        $this->columns[] = $sql;
    }

    public function addCommand(string $command): void
    {
        $this->commands[] = $command;
    }

    public function toSql(): string
    {
        $columnsSql = [];

        foreach ($this->columns as $col) {
           if ($col instanceof Column) {
                $columnsSql[] = $col->toSql();
            } else {
                $columnsSql[] = $col;
            }
        }

        //  echo "<pre>";
        //     echo sprintf('CREATE TABLE %s (%s)', $this->table, implode(', ', $columnsSql));
        //     //print_r($params);
        //     die(); 

        return sprintf('CREATE TABLE %s (%s)', $this->table, implode(', ', $columnsSql));
    }

   public function toAlterSql(): string
    {
        $columnsSql = [];

        foreach ($this->commands as $com) {
           if ($com instanceof Column) {
                $columnsSql[] = $com->toSql();
            } else {
                $columnsSql[] = $com;
            }
        }

        
        //  echo "<pre>";
        //     echo sprintf('ALTER TABLE %s %s', $this->table, implode(', ', $columnsSql));
        //     //print_r($params);
        //     die(); 


        return sprintf('ALTER TABLE %s %s', $this->table, implode(', ', $columnsSql));
    }


}


?>