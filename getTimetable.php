<?php
include 'vendor/autoload.php';
include 'functions.php';
//echo json_encode($_GET, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."<br>";
ini_set('memory_limit', '-1');
$myfile = fopen("data/Timetable2022.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("data/Timetable2022.json"));
fclose($myfile);
$timetable = collect(json_decode($file, true));
$timetable = groupCollapsing($timetable);//FIXME: Общая психология пропала для препода
$timetable = $timetable
    //->where('dayDate', $currentDate)
    //->where("TeacherFIO", "Исаков Сергей Сергеевич")
    //->where("Department.code", "ИТ")
    ->filter(function ($lesson) {
        return str_contains($lesson['dayDate'], "." . getActiveMonth() . ".");
    })
    ->filter(function ($lesson) {
        return !array_key_exists('group', $_GET) || ($lesson['Group']['id'] == $_GET['group']);
    })->filter(function ($lesson) {
        if (array_key_exists('professor', $_GET)) {
            return ($lesson['Teacher']['id'] == $_GET['professor']);
        } elseif (!array_key_exists('group', $_GET)) {
            return ($lesson['Teacher']['name'] == "Исаков Сергей Сергеевич");
        } else {
            return true;
        }
    });
//->where("dayDate", "ИТ");
$timetable = collapseSimilarities($timetable)
    ->sortBy(['TimeStart'])
    //->values()
    ->groupBy(['dayDate']);

echo json_encode($timetable->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
