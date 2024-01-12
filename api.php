<?php
header('Content-Type: application/json');
include 'vendor/autoload.php';
include 'functions.php';
$route = explode('/', $_SERVER['PATH_INFO']);
$response = null;
$optionalJSONArguments = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE | JSON_NUMERIC_CHECK;
try {
    if (end($route) === 'getProfessors') {
        echo getProfessors()->toJson($optionalJSONArguments);
    } else if (end($route) === 'getBuildings') {
        echo getBuildings()->toJson($optionalJSONArguments);
    } else if (end($route) === 'getGroups') {
        echo getGroups()->toJson($optionalJSONArguments);
    } else if (end($route) === 'getTimetable') {
        echo getPreparedTimetable()->values()->toJson($optionalJSONArguments);
    } else if (end($route) === 'getRoomDistribute') {
        echo getPreparedTimetable()
            ->values()
            ->toJson($optionalJSONArguments);
    }
} catch (PDOException $e) {
    die($e->getMessage());
}