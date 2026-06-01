<?php


namespace App\Core\Database;

use App\Core\Database\Exceptions\DatabaseException;

use PDO;


class QueryBuilder
{
    protected string $table;

    protected array $columns = ['*'];

    protected array $where = [];

    protected array $joins = [];

    protected array $orderBy = [];

    protected ?int $limit = null;

    protected array $groupBy = [];

    protected array $having = [];

    protected bool $debug = false;

    protected static array $queryLog = [];

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

    // --------------------------- SELECT ROWS ------------------------------------- //

    public function selectRaw(string $expression): self
            {
                $this->columns[] = $expression;

                return $this;
            }

    // --------------------------- WHERE COLUMNS ------------------------------------- //

        public function where(string $column, string $operator, mixed $value):self
        {
            $this -> validateIdentifier($column);

            $allowedOperators = [
                '=',
                '!=',
                '>',
                '<',
                '>=',
                '<=',
                'LIKE'
            ];

            if(!in_array($operator, $allowedOperators))
                {
                    throw new DatabaseException("Invalid Operator");
                }

            
            $this-> where[] = [
                'type' => 'AND',
                'column' => $column,
                'operator' => $operator,
                'value' => $value
            ];

            return $this;
        }

// --------------------------- OR_WHERE COLUMNS ------------------------------------- //

public function orWhere(string $column, string $operator, mixed $value): self
{
    $this -> validateIdentifier($column);

            $allowedOperators = [
                '=',
                '!=',
                '>',
                '<',
                '>=',
                '<=',
                'LIKE'
            ];

            if(!in_array($operator, $allowedOperators))
                {
                    throw new DatabaseException("Invalid Operator");
                }

            
            $this-> where[] = [
                'type' => 'OR',
                'column' => $column,
                'operator' => $operator,
                'value' => $value
            ];

            return $this;
}

// --------------------------- ORDER_BY COLUMNS ------------------------------------- //

public function orderBy(string $column, string $direction = 'ASC'): self
{
    $this -> validateIdentifier($column);

    $direction = strtoupper($direction);

    if(!in_array($direction, ['ASC', 'DESC']))
        {
            throw new DatabaseException("Invalid Order Direction");
        }

    
    $this-> orderBy[] = [
                'column' => $column,
                'direction' => $direction
            ];

            return $this;

}

// --------------------------- LIMIT COLUMNS ------------------------------------- //

public function limit(int $limit): self
{
    $this->limit = $limit;

    return $this;
}

// --------------------------- JOIN COLUMNS ------------------------------------- //

public function join(
    string $table,
    string $firstColumn,
    string $operator,
    string $secondColumn
): self
{
    $this->validateIdentifier($table);

    $this->joins[] = [
        'type' => 'INNER',
        'table' => $table,
        'first' => $firstColumn,
        'operator' => $operator,
        'second' => $secondColumn
    ];

    return $this;
}

// --------------------------- LEFT JOIN COLUMNS ------------------------------------- //

public function leftJoin(
    string $table,
    string $firstColumn,
    string $operator,
    string $secondColumn
): self
{
    $this->validateIdentifier($table);

    $this->joins[] = [
        'type' => 'LEFT',
        'table' => $table,
        'first' => $firstColumn,
        'operator' => $operator,
        'second' => $secondColumn
    ];

    return $this;
}

// --------------------------- GROUP_BY COLUMNS ------------------------------------- //

public function groupBy(
    string ...$columns
): self
{
    foreach ($columns as $column)
    {
        $this->validateIdentifier($column);
    }

    $this->groupBy = $columns;

    return $this;
}

// --------------------------- HAVING COLUMNS ------------------------------------- //

public function having(
    string $column,
    string $operator,
    mixed $value
): self
{
    $allowedOperators = [
        '=',
        '!=',
        '>',
        '<',
        '>=',
        '<='
    ];

    if (!in_array(
        $operator,
        $allowedOperators
    )) {
        throw new DatabaseException(
            'Invalid Having Operator'
        );
    }

    $this->having[] = [
        'column' => $column,
        'operator' => $operator,
        'value' => $value
    ];

    return $this;
}

// --------------------------- DEBUG MODE ------------------------------------- //

public function enableDebug(): self
    {
        $this->debug = true;

        return $this;
    }

// --------------------------- GET QUERY LOGGING ------------------------------------- //

public static function getQueryLog(): array
    {
        return self::$queryLog;
    }

// --------------------------- CLEAR QUERY LOGGING------------------------------------- //

public static function clearQueryLog(): void
    {
        self::$queryLog = [];
    }

// --------------------------- RIGHT JOIN COLUMNS ------------------------------------- //

// --------------------------- GET MANY RECORDS ------------------------------------- //

    public function get(): array
    {
        $pdo = Conncetion::connect();

        $columns = implode(', ', $this->columns);

        $whereClause = '';
        $params = [];

        $joinClause = '';

        $orderClause = '';

        $limitClause = '';

        $groupByClause = '';

        $havingClause = '';
        $havingParams = [];

        if(!empty($this->where))
            {
                $conditions = [];

                foreach($this->where as $index => $where)
                    {
                        $placeholder = "value{$index}";

                        $conditions[] = "{$where['column']} {$where['operator']} :{$placeholder}";

                        $params[$placeholder] = $where['value'];
                    }


                $whereClause = ' WHERE '. implode(' AND ', $conditions);
            }



        if(!empty($this->joins))
            {
                foreach($this->joins as $join)
                    {
                        $joinClause .= 
                        "{$join['type']} JOIN {$join['table']}  ON {$join['first']} {$join['operator']} {$join['second']}";
                    }
            }


        if (!empty($this->orderBy))
            {
                $parts = [];

                foreach ($this->orderBy as $order)
                {
                    $parts[] =
                        "{$order['column']}
                        {$order['direction']}";
                }

                $orderClause =
                    ' ORDER BY ' .
                    implode(', ', $parts);
            }


         if ($this->limit !== null)
            {
                $limitClause = " LIMIT {$this->limit}";
            }

        
        if (!empty($this->groupBy))
            {
                $groupByClause =
                    ' GROUP BY ' .
                    implode(
                        ', ',
                        $this->groupBy
                    );
            }



        if (!empty($this->having))
            {
                $conditions = [];

                foreach ($this->having as $index => $having)
                {
                    $placeholder = "having{$index}";

                    $conditions[] = "{$having['column']} {$having['operator']} :{$placeholder}";

                    $havingParams[$placeholder] =  $having['value'];
                }

                $havingClause =
                    ' HAVING ' .
                    implode(
                        ' AND ',
                        $conditions
                    );
            }





            $query = "SELECT {$columns} FROM {$this->table} {$joinClause} {$whereClause} {$groupByClause} {$havingClause} {$orderClause} {$limitClause}";



            if ($this->debug) {
                    echo "<pre>";
                    echo $query . PHP_EOL;
                    print_r($params);
                }



          /* echo "<pre>";
            echo $query;
            print_r($params);
            die(); */


            $stmt = $pdo->prepare($query);
            
            $params = array_merge(
                    $params,
                    $havingParams
                );

    // *********************************************************************
            self::$queryLog[] = [
                    'query' => $query,
                    'params' => $params,
                ]; // ***HERE AN MODIFACTION IS REQUIRED -> NOW RECORDS ONLY AS LOG THE LAST STATEMENT (THE NEW MODIFICATION -> TO RECORD ALL THE STATEMENTS THAT HAS BEEN IMPLEMENTED) ****
    // *********************************************************************
            $stmt->execute($params);

            return $stmt->fetchAll();

        
    }

    // --------------------------- GET FIRST RECORD ------------------------------------- //

    public function first(): array|null
    {
        $result = $this->get();

        return $result[0] ?? null;
    }

    // --------------------------- GET COUNT RECORD ------------------------------------- //

    public function count(): int
        {
            $pdo = Conncetion::connect();

            $params = [];

            $whereClause = '';

            if (!empty($this->where))
                {
                    $conditions = [];

                    foreach ($this->where as $index => $where)
                    {
                        $placeholder = "value{$index}";

                        if ($index === 0)
                        {
                            $conditions[] = "{$where['column']} {$where['operator']} :{$placeholder}";
                        }
                        else
                        {
                            $conditions[] = "{$where['type']} {$where['column']} {$where['operator']} :{$placeholder}";
                        }

                        $params[$placeholder] = $where['value'];
                    }

                    $whereClause =
                        ' WHERE ' .
                        implode(
                            ' ',
                            $conditions
                        );
                }

                $query = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";

                $stmt = $pdo->prepare($query);

                $stmt->execute($params);

                return (int) $stmt->fetch()['total'];
            }

            
    // --------------------------- CHECKS IF EXISTS ------------------------------------- //

    public function exists(): bool
        {
            return $this->count() > 0;
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

    // ----------------------------- PAGINATION ------------------------------------- //

    public function paginate(int $perPage, int $page = 1): array
        {
            $pdo = Conncetion::connect();

            $offset = ($page - 1) * $perPage;

            $columns = implode(', ', $this->columns);

            $where = $this->buildWhere();
            $whereClause = $where['sql'];
            $params = $where['params'];

            $query = "SELECT {$columns} FROM {$this->table} {$whereClause} LIMIT {$perPage} OFFSET {$offset}";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            $data = $stmt->fetchAll();

            // total count
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";

            $stmt2 = $pdo->prepare($countQuery);
            $stmt2->execute($params);

            $total = $stmt2->fetch()['total'];

            return [
                'data' => $data,
                'total' => (int) $total,
                'page' => $page,
                'per_page' => $perPage,
                'last_page' => ceil($total / $perPage),
            ];
        }

    // ----------------------------- BUILD WHERE FOR ALL OPERATION (TILL NOW ONLY PAGINATION USED NOT ALL) ------------------------------------- //

    private function buildWhere(): array
        {
            $conditions = [];
            $params = [];

            foreach ($this->where as $index => $where) {

                $placeholder = "value{$index}";

                if ($index === 0) {
                    $conditions[] = "{$where['column']} {$where['operator']} :{$placeholder}";
                } else {
                    $conditions[] = "{$where['type']} {$where['column']} {$where['operator']} :{$placeholder}";
                }

                $params[$placeholder] = $where['value'];
            }

            $sql = '';

            if (!empty($conditions)) {
                $sql = ' WHERE ' . implode(' ', $conditions);
            }

            return [
                'sql' => $sql,
                'params' => $params
            ];
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