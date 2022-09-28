<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getTimetable();
$timetable = $timetable
    ->pluck("Group")->sortBy('name')
    ->unique()->values();

echo json_encode($timetable->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
