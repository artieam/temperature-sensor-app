<?php

use Http\ApiHandler;
use Http\AppResponse;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

include_once $_SERVER['DOCUMENT_ROOT'] . '/initial/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestMethodArray = $_REQUEST;
$apiHandler = new ApiHandler();

if ($requestMethod === "GET") {

    if (isset($requestMethodArray['query'])) {
        $result = $apiHandler->execCommand($requestMethodArray['query']);
    } else {
        print('OK');
    }

} elseif ($requestMethod === "POST") {
    $json_string_data = file_get_contents('php://input');
    $decoded_data = json_decode($json_string_data, true) ?? [];

    if (isset($requestMethodArray['query'])) {
        $result = $apiHandler->execCommand($requestMethodArray['query'], $decoded_data);
        print(json_encode($result));
    }
} else {
    $returnArray = AppResponse::getResponse('405');
    print(json_encode($returnArray));
}
