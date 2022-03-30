<?php
include 'vendor/autoload.php';
include 'functions.php';
ini_set('memory_limit', '-1');
//$file = readfile('Result_152.json');
//$file = readfile('Result_200.json');
$myfile = fopen("Result_200.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("Result_200.json"));
fclose($myfile);
$timetable = collect(json_decode($file, true));
$timetable = groupCollapsing($timetable);
$nextDay = date("d.m.Y", strtotime("+1 day", strtotime(date("d.m.Y"))));
$timetable = $timetable
    ->where('dayDate', $nextDay)
    ->where("Department.code", "ИТ");
?>
<pre><code><?php
        ?></code></pre>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <title>Document</title>
    <style>
        #main {
            scrollbar-base-color: red;
        }

        #main {
            height: 100vh;
            overflow-y: scroll;
        }
    </style>
</head>
<body>

<div class="row">

    <div class="col-md-9 mx-auto">
        <h1 class="display-3">
            На завтра <span class="display-6">(<?=$nextDay?>)</span>
        </h1>
        <?php
        $timetable->pluck('Coords')->unique()->each(function ($value) {
            echo "<span class='badge fs-6 fw-normal bg-info text-dark mx-1 my-2'>{$value['room']['index']}</span>";
        });
        ?>
        <br>
        <?php
        $timetable->pluck('Group')->unique()->each(function ($value) {
            echo "<span class='badge fs-6 fw-normal bg-warning text-dark mx-1 my-2'>{$value['name']}</span>";
        });
        ?>

        <div class="list-group" id="main">
            <?php
            $timetable->each(function ($value) {
                echo "<div class='list-group-item list-group-item-action' aria-current='true'>
                    <div class='d-flex w-100 justify-content-between'>
                        <h5 class='mb-1'>{$value['Discipline']}</h5>
                        <small class='text-muted'>{$value['Group']['name']}</small>
                    </div>
                    <p class='mb-1'>{$value['Teacher']['name']}</p>
                    <i class='bi bi-door-open text-primary'>{$value['Coords']['room']['index']}</i>
                    <small>{$value['TimeStart']}-{$value['TimeEnd']}</small>
                </div>";
            });
            ?>
        </div>
    </div>
</div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>


