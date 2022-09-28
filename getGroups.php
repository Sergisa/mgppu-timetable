<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getTimetable();
$timetable = $timetable
    ->pluck("Group")
    ->unique()->values();

echo json_encode($timetable->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
