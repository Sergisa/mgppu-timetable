<?php
include 'vendor/autoload.php';
include 'functions.php';

ini_set('memory_limit', '-1');
$myfile = fopen("data/Timetable2022.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("data/Timetable2022.json"));
fclose($myfile);
$timetable = collect(json_decode($file, true));
$timetable = groupCollapsing($timetable);
$timetable = $timetable
    ->pluck("Group")
    ->unique()->values();

echo json_encode($timetable->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
