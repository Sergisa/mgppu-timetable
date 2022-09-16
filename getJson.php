<?php
include 'vendor/autoload.php';
include 'functions.php';

ini_set('memory_limit', '-1');
$myfile = fopen("data/Timetable2022.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("data/Timetable2022.json"));
fclose($myfile);
$timetable = collect(json_decode($file, true));
$timetable = groupCollapsing($timetable);
$currentDate = date("d.m.Y");
$timetable = $timetable
    //->where('dayDate', $currentDate)
    ->where("TeacherFIO", "Исаков Сергей Сергеевич")
    ->where("Department.code", "ИТ");
//->where("dayDate", "ИТ");
$timetable = collapseSimilarities($timetable)
    ->sortBy(['TimeStart'])
    //->values()
    ->groupBy(['dayDate' ], true);

echo json_encode($timetable->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
