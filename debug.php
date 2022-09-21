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
//$currentDate = date("d.m.Y", strtotime("+1 day", strtotime(date("d.m.Y"))));
/*$timetable = $timetable;
    ->where("Department.code", "ИТ")
    ->where("TeacherFIO", "Исаков Сергей Сергеевич");*/
//$timetable = collapseSimilarities($timetable)->groupBy('dayDate')->sortBy('TimeStart');
echo json_encode($timetable->pluck('Teacher')->unique()->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="dist/css/style.css">
    <style>
        .debug {
            color: #FCBB6D;
        }
    </style>
</head>
<body>
<pre class="debug"><code></code></pre>

</body>
</html>
