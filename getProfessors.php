<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getTimetable();
$professorsList = $timetable
    ->pluck("Teacher")
    ->unique()->values()->toArray();
echo json_encode($professorsList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
