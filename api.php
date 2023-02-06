<?php
header('Content-Type: application/json');
include 'vendor/autoload.php';
include 'DataHandler.php';
$route = explode('/', $_SERVER['PATH_INFO']);
$response = null;
try {
    if (end($route) === 'getProfessors') {
        echo DataHandler::getInstance()->getProfessors()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
    } else if (end($route) === 'getBuildings') {
        echo DataHandler::getInstance()->getBuildings()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
    } else if (end($route) === 'getGroups') {
        echo DataHandler::getInstance()->getGroups()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
    } else if (end($route) === 'getTimetable') {
        echo DataHandler::getInstance()->getPreparedTimetable()->values()->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
    } else if (end($route) === 'getRoomDistribute') {
        echo DataHandler::getInstance()->getPreparedTimetable()
            ->values()
            ->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
    }
} catch (PDOException $e) {
    die($e->getMessage());
}