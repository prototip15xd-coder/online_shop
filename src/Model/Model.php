<?php

declare(strict_types=1);

namespace Model;

use PDO;

abstract class Model
{
    protected static PDO $connection;

    public static function getPDO()
    {
        static::$connection = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');

        return static::$connection;
    }

    abstract static protected function getTableName(): string;
}