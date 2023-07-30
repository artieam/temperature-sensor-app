<?php

declare(strict_types=1);

namespace Controller;

use Exception;
use Http\AppResponse;
use Service\SensorInformationService;

class SensorController
{
    private SensorInformationService $service;

    public function __construct()
    {
        $this->service = new SensorInformationService();
    }

    /**
     * POST /api/push (API #1)
     */
    public function save($params): array
    {
        if (!isset($params['reading']['sensor_uuid'], $params['reading']['temperature'])) {
            return AppResponse::getResponse('500');
        }

        $isSaved = $this->service->saveSensorResult($params['reading']['sensor_uuid'], $params['reading']['temperature']);
        if (!$isSaved) {
            return AppResponse::getResponse('500');
        }

        return AppResponse::getResponse('200');
    }

    /**
     * GET /sensor/read/%sensor_ip% (API #2)
     *
     * @throws Exception
     */
    public function read(string $sensorIp): void
    {
        header("Content-Type: text/csv; charset=UTF-8");
        print(implode(', ', $this->service->getInformation($sensorIp)));
    }

    /**
     * POST /sensor/average/all (API #3)
     */
    public function avrAll($params): array
    {
        if (!isset($params['day_range'])) {
            return AppResponse::getResponse('500');
        }

        return ['avr_temperature' => $this->service->getAverageTemperature((int) $params['day_range'])];
    }

    /**
     * GET /sensor/average/bySensor/%sensor_uuid% (API #4)
     */
    public function avrBySensor(string $sensorUuid): void
    {
        print('avr_temperature: ' . $this->service->getAverageTemperature(0, $sensorUuid));
    }
}
