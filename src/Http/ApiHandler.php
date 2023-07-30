<?php

declare(strict_types=1);

namespace Http;

use Controller\SensorController;

class ApiHandler
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [
            '/sensor/read/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}' => [
                'class' => SensorController::class,
                'method' => 'read',
                'pathArg' => function($path) {
                    $path = explode('/', $path);
                    return end($path);
                }],
            '/api/push' => ['class' => SensorController::class, 'method' => 'save'],
            '/sensor/average/all' => ['class' => SensorController::class, 'method' => 'avrAll'],
            '/sensor/average/bySensor/[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}'
                => [
                    'class' => SensorController::class,
                    'method' => 'avrBySensor',
                    'pathArg' => function($path) {
                        $path = explode('/', $path);
                        return end($path);
                    }
                ],
        ];
    }

    /**
     * Execute api method
     *
     * @param string $method
     * @param array $params
     *
     * @return array
     */
    public function execCommand(string $method, array $params = [])
    {
        // get the actual function name (if necessary) and the class it belongs to.
        $returnArray = $this->getCommand($method);

        // if we don't get a function back, then raise the error
        if ($returnArray['success'] === false) {
            return $returnArray;
        }

        $class = $returnArray['dataArray']['class'];
        $methodName = $returnArray['dataArray']['method'];
        if ($returnArray['dataArray']['pathArg']) {
            $params = $returnArray['dataArray']['pathArg'];
        }

        // Execute User Profile Commands
        return (new $class())->$methodName($params);
    }

    /**
     * get the actual function name and the class it belongs to.
     */
    private function getCommand(string $path): array
    {
        $returnArray = AppResponse::getResponse('405');
        foreach ($this->routes as $route => $routeSet) {
            if (preg_match('/' . addcslashes($route, '/') . '/', $path)) {
                $returnArray = AppResponse::getResponse('200');
                $returnArray['dataArray'] = [
                    'class' => $routeSet['class'],
                    'method' => $routeSet['method'],
                    'pathArg' => (isset($routeSet['pathArg']) && is_callable($routeSet['pathArg']))
                        ? $routeSet['pathArg']($path)
                        : null,
                ];
                break;
            }
        }

        return $returnArray;
    }
}
