<?php


namespace App\Core\Database;

use App\Core\Database\Exceptions\DatabaseException;

use PDO;


class QueryBuilder
{
    protected string $table;

    protected array $columns = ['*'];

    protected array $where = [];

    public function __construct(string $table)
    {
        $this -> validateIdentifier($table);
        $this -> table = $table;
    }


    // --------------------------- SELECT COLUMNS ------------------------------------- //


        public function select(string ...$columns):self
        {
            foreach($columns as $column)
                {
                    $this -> validateIdentifier($column);

                }

                $this -> columns = $columns;

                return $this;
        }


        // --------------------------- WHERE COLUMNS ------------------------------------- //

        public function where(string $column, mixed $value):self
        {
            $this -> validateIdentifier($column);
            
            $this-> where[] = [
                'column' => $column,
                'value' => $value
            ];

            return $this;
        }

// --------------------------- GET MANY RECORDS ------------------------------------- //

    public function get(): array
    {
        $pdo = Conncetion::connect();

        $columns = implode(',', $this->columns);

        $whereClause = '';
        $params = [];

        if(!empty($this->where))
            {
                $conditions = [];

                foreach($this->where as $index => $where)
                    {
                        $placeholder = "value{$index}";

                        $conditions[] = "{$where['column']} = :{$placeholder}";

                        $params[$placeholder] = $where['value'];
                    }


                $whereClause = ' WHERE '. implode(' AND ', $conditions);
            }

            $query = "SELECT {$columns} FROM {$this->table} {$whereClause}";


           /*echo "<pre>";
            echo $query;
            print_r($params);
            die(); */


            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll();

        
    }

    // --------------------------- GET FIRST RECORD ------------------------------------- //

    public function first(): array|null
    {
        $result = $this->get();

        return $result[0] ?? null;
    }

    // --------------------------- INSERT ------------------------------------- //

    public function insert(array $data): bool
    {
        $pdo = Conncetion::connect();

        foreach(array_keys($data) as $column){
            $this -> validateIdentifier($column);
        }

        $columns = implode(',', array_keys($data));

        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $pdo->prepare($query);

        return $stmt->execute($data);
    }

    // --------------------------- UPDATE ------------------------------------- //

    public function update(array $data): bool
    {

        $pdo = Conncetion::connect();

        foreach(array_keys($data) as $column){
            $this -> validateIdentifier($column);
        }


        $set = [];

        foreach($data as $column => $value){
            $set [] = "{$column} = :{$column}";
        }

        $setClause = implode(',', $set);

        $params = $data;

        $conditions = [];

        foreach($this->where as $index => $where){
            $placeholder = "where{$index}";

            $conditions[] = "{$where['column']} = :{$placeholder}";

            $params[$placeholder] = $where['value'];

        }

        $whereClause = implode('AND', $conditions);

        $query = "UPDATE {$this->table} SET {$setClause} WHERE {$whereClause}";

        $stmt = $pdo->prepare($query);

        return $stmt->execute($params);
    }

    // --------------------------- DELETE ------------------------------------- //


    public function delete(): bool
    {
        $pdo = Conncetion::connect();

        $conditions = [];
        $params = [];

        foreach($this->where as $index => $where){
            $placeholder = "where{$index}";

            $conditions[] = "{$where['column']} = :{$placeholder}";
            
            $params[$placeholder] = $where['value'];
        }


        $whereClause = implode('AND', $conditions);

        $query = "DELETE FROM {$this->table} WHERE {$whereClause}";

        $stmt = $pdo->prepare($query);

        return $stmt->execute($params);
    }

    // ----------------------------- VALIDATE TABLE/COLUMN NAMES ------------------------------------- //

    private function validateIdentifier(string $name): void
    {
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $name))
            {
                throw new DatabaseException("Invalid indentifier: {$name}");
            }
    }







}   






?>