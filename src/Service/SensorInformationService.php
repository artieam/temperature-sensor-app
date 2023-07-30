<?php

declare(strict_types=1);

namespace Service;

use db\PgPdoConnection;
use Exception;
use PDO;

class SensorInformationService
{
    public const RESULTS_TABLE = 'sensor_results';
    public const READING_TABLE = 'sensor_reading_counters';

    private PDO $db;

    public function __construct()
    {
        $this->db = (new PgPdoConnection())->connect();
    }

    public function saveSensorResult(string $sensorUuid, $temperature)
    {
        try {
            return $this->db->query("
                INSERT INTO " . self::RESULTS_TABLE . " (sensor_uuid, temperature) 
                VALUES ('$sensorUuid', $temperature);
            ");
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getInformation(string $sensorIp): array
    {
        $readingId = $this->incReading($sensorIp);
        $stNum = -10;
        $endNum = 80;
        $mul = 100;
        return [$readingId, random_int($stNum * $mul, $endNum * $mul) / $mul];
    }

    private function incReading(string $sensorIp): int
    {
        $table = self::READING_TABLE;

        $cnt = (int) $this->db->query("SELECT cnt FROM $table WHERE sensor_ip = '$sensorIp'")->fetchColumn();
        ++$cnt;

        $this->db->query("
            INSERT INTO $table (sensor_ip, cnt) 
            VALUES ('$sensorIp', $cnt)
            ON CONFLICT (sensor_ip) DO UPDATE 
                SET cnt = $cnt
        ");

        return $cnt;
    }

    public function getAverageTemperature(int $dayRange, ?string $sensorUuid = null): float
    {
        if ($sensorUuid) {
            $where = "WHERE sensor_uuid = '$sensorUuid' AND created_at >= (now() - interval '1 hour')";
        } else {
            $where = "WHERE created_at >= (now() - interval '$dayRange days')";
        }

        $result = $this->db->query("
            SELECT avg(temperature)
            FROM " . self::RESULTS_TABLE . "
            $where
        ")->fetchColumn();

        return round((float) $result, 2);
    }
}
