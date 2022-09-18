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
$timetable = $timetable
    //->where('dayDate', $currentDate)
    ->where("TeacherFIO", "Исаков Сергей Сергеевич")
    ->where("Department.code", "ИТ");
$timetable = collapseSimilarities($timetable)
    ->sortBy(['Number'])
    ->sortByDate('dayDate')
    ->groupBy('dayDate');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="dist/css/style.css">
    <title>Document</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="container">
<div class="row">
    <div class="col-8 calendar py-1" id="monthGrid"></div>

    <div id="listDays" class="col-4">
        <ul class="list-group list-group-flush bg-opacity-100">
            <?php
            foreach ($timetable as $date => $lessons) {
                echo "<li class='list-group-item' data-date='$date'>";
                echo "<div class='labels'>
                    <span class='date me-1'>" . convertDate('d.m', $date) . "</span>";
                foreach (collect($lessons)->pluck("Type") as $type) {
                    echo "<span class='type me-1'>" . mb_substr($type, 0, 1) . "</span>";
                }
                echo "</div>";
                foreach ($lessons as $lesson) {
                    $lessonSign = getLessonSignature($lesson);
                    $groupsSign = getGroupsSignature($lesson);
                    $lessonIndex = getLessonIndex($lesson);
                    echo "<div class='lesson' data-time='{$lesson['TimeStart']}'><b>{$lessonIndex}.</b> {$lessonSign}<span class='groupCode'>{$groupsSign}</span></div>";
                }
                echo "</li>";
            }
            ?>
        </ul>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/dateDriver.js"></script>
<script src="dist/js/calendar.js"></script>
<script src="dist/js/dayList.js"></script>
<script>
    const lines = $('.line');
    const lessonAmount = 5;
    const topHeight = 8;

    $(document).ready(function () {
        scrollToCurrentDate();
    })

    $.getJSON('getJson.php', function (data) {
        console.log(data)
        window.lessonsTimetable = data
        generateGrid();
    }).fail(function (data) {
        console.error(data)
    })

</script>
</html>


