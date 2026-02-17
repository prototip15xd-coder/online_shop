<?php

namespace Model;

abstract class Model
{
    protected \PDO $connection;

    public function __construct()
    {
        $this->connection = new \PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
    }

    abstract protected function getTableName(): string;
}