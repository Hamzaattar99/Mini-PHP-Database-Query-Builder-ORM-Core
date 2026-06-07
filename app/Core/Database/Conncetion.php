<?php

namespace App\Core\Database;

use PDO;
use PDOException;
use App\Core\Database\Exceptions\DatabaseException;


class Conncetion
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if(self::$pdo === null)
            {
                $config = require __DIR__.'/../../../config/database.php';

                try
                {
                    self:: $pdo = new PDO(
                        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                        $config['username'],
                        $config['password']
                    );

                    self::$pdo->setAttribute(
                        PDO::ATTR_ERRMODE,
                        PDO::ERRMODE_EXCEPTION
                    );

                    self::$pdo->setAttribute(

                        PDO::ATTR_DEFAULT_FETCH_MODE,
                        PDO::FETCH_ASSOC

                    );


                }
                catch(PDOException $ex)
                {
                    throw DatabaseException::connectionError($ex);
                }
            }

            return self::$pdo;
    }
}


?>