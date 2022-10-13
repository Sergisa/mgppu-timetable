<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getPreparedTimetable()->groupBy('dayDate');
$_monthsList = getMonths()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            <?= $_monthsList[(int)getActiveMonth()] ?>
            <a href="?<?= getNextMonthLink() ?>" class="<?= getActiveMonth() < 12 ? "d-inline" : 'invisible' ?>">
                <i class="bi bi-arrow-right-square fs-1 float-end"></i>
            </a>
            <a href="?<?= getPreviousMonthLink() ?>" class="<?= getActiveMonth() > 1 ? "d-inline" : 'invisible' ?>">
                <i class="bi bi-arrow-left-square fs-1 float-end"></i>
            </a>
        </h1>
        <p class="lead text-primary d-md-inline m-0 mb-md-3 subtitle">
            <?php
            if (isTeacherTimetable()) {
                if (!array_key_exists('professor', $_GET)) {
                    echo "Исаков Сергей Сергеевич ";
                } else {
                    echo getTeacherById($_GET['professor']) . " ";
                }
            } elseif (!array_key_exists('group', $_GET)) {
                echo "Исаков Сергей Сергеевич ";
            } else {
                echo "";
            }
            ?>
        </p>
        <p class="lead text-primary d-md-inline m-0 mb-md-3 subtitle">
            <?php
            echo array_key_exists('group', $_GET) ? getGroupById($_GET['group']) : "";
            ?>
        </p>
        <div class="calendar p-1 loading" id="monthGrid"></div>
    </div>
    <div id="listDays" class="col-12 col-md-4">
        <?php

        try {
            echo getBlade()->run("dayList", [
                    "timetable" => getData()->groupBy('dayDate')
            ]);
        } catch (Exception $e) {}
        ?>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    $(document).on('ready', function () {
        //scrollToCurrentDate();
    })
    const urlParams = new URLSearchParams(window.location.search);
    const rqObject = {}
    if (urlParams.has('professor')) {
        rqObject.professor = urlParams.get('professor');
    }
    if (urlParams.has('group')) {
        rqObject.group = urlParams.get('group');
    }
    if (urlParams.has('month')) {
        rqObject.month = urlParams.get('month');
    }
    $.getJSON('getTimetable.php', rqObject).done(function (data) {
        console.log(data)
        window.lessonsTimetable = data
        generateGrid($('#monthGrid').removeClass('loading'), urlParams.has('month') ? parseInt(urlParams.get('month')) - 1 : new Date().getMonth());
        $('#monthGrid .day').on('click', function () {
            console.log(this.dataset.date)
            scrollToDate(this.dataset.date)
        })
    }).fail(function (data) {
        console.info(data.responseText)
    })
</script>
</html>


