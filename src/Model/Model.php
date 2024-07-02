<?php
class Model
{
    public \PDO $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}