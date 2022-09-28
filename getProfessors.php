<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getTimetable();
$professorsList = $timetable
    ->pluck("Teacher")
    ->unique()->sortBy('name')->values()->toArray();
echo json_encode($professorsList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
