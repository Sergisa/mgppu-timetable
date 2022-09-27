<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getData();
$_monthsList = getMonths()
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
<body class="container py-2">
<div class="row">

    <div class="col-12 col-md-8">
        <h1 class="fw-bolder month-title text-primary d-md-inline">
            <a href="/">
                <i class="bi bi-backspace"></i>
            </a>
            <?= $_monthsList[(int)date('m')] ?>
        </h1>
        <p class="lead text-primary d-md-inline m-0 mb-md-3">
            <?php
            if (isTeacherTimetable()) {
                echo getTeacherById($_GET['professor']) . " ";
            } elseif (!array_key_exists('group', $_GET)) {
                echo "Исаков Сергей Сергеевич ";
            } else {
                echo "";
            }
            echo array_key_exists('group', $_GET) ? getGroupById($_GET['group']) : "";
            ?>
        </p>
        <div class="calendar py-1" id="monthGrid"></div>
    </div>
    <div id="listDays" class="col-12 col-md-4">
        <ul class="list-group list-group-flush bg-opacity-100">
            <?php
            if ($timetable->isEmpty()) {
                echo "<h2 class='text-primary text-center mt-4'>Нет пар</h2>";
            }
            foreach ($timetable as $date => $lessons) {
                echo "<li class='list-group-item' data-date='$date'>";
                echo "<div class='labels'>
                    <span class='date me-1'>" . convertDate('d.m', $date) . "</span>";
                foreach (collect($lessons)->pluck("Type") as $type) {
                    echo "<span class='type me-1'>" . getLessonTypeSignature($type) . "</span>";
                }
                echo "</div>";
                foreach ($lessons as $lesson) {
                    $lessonSign = getLessonSignature($lesson);
                    $groupsSign = getGroupsSignature($lesson);
                    $courseSign = getCourseNumber($lesson['Group'][0]['name']);
                    $teacherSign = getTeacherSignature($lesson);
                    $lessonIndex = getLessonIndex($lesson);
                    if (isTeacherTimetable()) {
                        echo "<div class='lesson' data-time='{$lesson['TimeStart']}'><b>{$lessonIndex}.</b> {$lessonSign}<span class='groupCode'>$groupsSign $courseSign</span></div>";
                    } else if (isGroupTimetable()) {
                        echo "<div class='lesson' data-time='{$lesson['TimeStart']}'><b>{$lessonIndex}.</b> {$lessonSign}<span class='groupCode'>$teacherSign</span></div>";
                    }
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
<script src="dist/js/bundle.js"></script>
<script>
    $(document).ready(function () {
        scrollToCurrentDate();
    })
    const urlParams = new URLSearchParams(window.location.search);
    const rqObject = {}
    if (urlParams.has('professor')) {
        rqObject.professor = urlParams.get('professor');
    }
    if (urlParams.has('group')) {
        rqObject.group = urlParams.get('group');
    }
    $.getJSON('getTimetable.php', rqObject).done(function (data) {
        console.log(data)
        window.lessonsTimetable = data
        generateGrid(new Date().getMonth());
        $('#monthGrid .day').click(function () {
            console.log(this.dataset.date)
            scrollToDate(this.dataset.date)
        })
    }).fail(function (data) {
        console.info(data.responseText)
    })
</script>
</html>


