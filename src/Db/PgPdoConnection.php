<?php

namespace db;

use PDO;
use PDOException;

class PgPdoConnection
{
    private array $config;

    public function __construct()
    {
        $config = include('config/database.php');
        $this->config = $config['postgresql'];
    }

    public function connect(): PDO
    {
        ['host' => $host, 'db' => $db, 'user'=> $user, 'password' => $password] = $this->config;
        try {
            $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
            return new PDO(
                $dsn,
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
