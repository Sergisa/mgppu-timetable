<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getData();
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

        .right-menu {
            position: fixed;
            right: -130px;
            top: 0;
            z-index: 10;
            height: 101%;
            display: flex;
            width: 260px;
            flex-direction: column;
            align-items: start;
            justify-content: center;
            border-radius: 50%;
            background: -moz-radial-gradient(#48484833, #33333370);
            font-weight: bolder;
            color: white;
        }

        .right-menu .menu-element:hover {
            color: #FCBB6D;
        }

        .right-menu .menu-element {
            margin: 30px 0;
            cursor: pointer;
        }
    </style>
</head>
<body class="container">
<div class="right-menu">
    <div class="menu-element">Скачать</div>
    <div class="menu-element">Распечатать расписание</div>
    <div class="menu-element">Изменить Группу</div>
    <div class="menu-element">Изменить преподавателя</div>
</div>
<div class="row">
    <div class="col-12 col-md-8 calendar py-1" id="monthGrid"></div>

    <div id="listDays" class="col-12 col-md-4">
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
<script src="dist/js/bundle.js"></script>
<script>
    $(document).ready(function () {
        scrollToCurrentDate();
    })

    $.getJSON('getJson.php', function (data) {
        console.log(data)
        window.lessonsTimetable = data
        generateGrid(new Date().getMonth());
        $('#monthGrid .day').click(function () {
            console.log(this.dataset.date)
            scrollToDate(this.dataset.date)
        })
    }).fail(function (data) {
        console.error(data)
    })
</script>
</html>


