<?php

namespace Console;

include_once 'src/Console/initial/autoload.php';

// Type "php src/Console/CreateTablesMigrationCommand.php" in console/terminal in the root of project
class CreateTablesMigrationCommand extends MigrationCommand
{
    public function run(): void
    {
        $this->db->query('
            CREATE TABLE IF NOT EXISTS sensor_results (
               id SERIAL,
               sensor_uuid varchar(36) NOT NULL,
               temperature decimal(5,2) NOT NULL,
               created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
               CONSTRAINT sensor_results_pkey PRIMARY KEY (id)
            );
        ');

        $this->db->query('
            CREATE TABLE IF NOT EXISTS sensor_reading_counters (
               sensor_ip varchar(128) NOT NULL,
               cnt int NOT NULL default 0,
               CONSTRAINT sensor_uuid_pkey PRIMARY KEY (sensor_ip)
            );
        ');

        echo 'Done!' . PHP_EOL;
    }
}

(new CreateTablesMigrationCommand())->run();
