<?php

use eftec\bladeone\BladeOne;

include 'vendor/autoload.php';
include 'functions.php';
ini_set('memory_limit', '-1');
$myfile = fopen("data/Timetable2022.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("data/Timetable2022.json"));
fclose($myfile);

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG); // MODE_DEBUG allows to pinpoint troubles.

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
    </style>
</head>
<body>
<div id="listDays" class="col-12 col-md-4">
    <?php
    try {
        echo $blade->run("dayList", [
            "timetable" => getData()->groupBy('dayDate')
        ]);
    } catch (Exception $e) {
    }
    ?>
</div>
</body>
</html>
