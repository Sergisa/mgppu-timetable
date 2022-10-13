<?php
include 'vendor/autoload.php';
include 'functions.php';
$route = explode('/', $_SERVER['REQUEST_URI']);
$response = null;
if (end($route) === 'getProfessors') {
    echo getProfessors()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
} else if (end($route) === 'getGroups') {
    echo getGroups()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
} else if (end($route) === 'getTimetable') {
    echo getPreparedTimetable()->values()->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
}

