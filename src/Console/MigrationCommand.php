<?php

namespace Console;

use db\PgPdoConnection;
use PDO;

abstract class MigrationCommand
{
    protected PDO $db;

    public function __construct() {
        $this->db = (new PgPdoConnection())->connect();
    }

    abstract public function run(): void;
}
