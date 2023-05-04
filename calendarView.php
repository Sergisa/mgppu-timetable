<?php
include 'vendor/autoload.php';
include 'functions.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900"
          rel="stylesheet">
    <link rel="stylesheet" href="dist/css/style.css">
    <style>
        .day.nearest {
            background-color: #ff000026 !important;
        }
    </style>
    <title>Календарь</title>
</head>
<body class="container py-2">
<?php
try {
    echo getBlade()->run("calendarLessonsPage", [
        "timetable" => getPreparedTimetable()->groupBy('dayDate')
    ]);
} catch (Exception $e) {
}
?>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    $(document).on('ready', function () {
        //scrollToCurrentDate();
    })
    const urlParams = getUrlParamsObject();

    function processTimetable(data) {
        window.lessonsTimetable = data
        generateGrid(
            $('#monthGrid').removeClass('loading'),
            (urlParams.month !== undefined) ? parseInt(urlParams.month) - 1 : new Date().getMonth(),
            (urlParams.year !== undefined) ? parseInt(urlParams.year) : new Date().getFullYear(),
        );
        $('#monthGrid .day').on('click', function () {
            console.log(this.dataset.date)
            scrollToElement(
                $("#listDays"),
                findDayBlock(this.dataset.date, $("#listDays")),
                true
            )
        })
    }

    $.getJSON('api.php/getTimetable', urlParams).done(processTimetable).fail(responseFail)

    function toggleLessonName(nameTag) {
        const indexTag = $(nameTag).find('b');
        if (indexTag.html() === nameTag.parentNode.dataset.index + ".") {
            indexTag.html(nameTag.parentNode.dataset.range)
        } else if (indexTag.html() === nameTag.parentNode.dataset.range) {
            indexTag.html(nameTag.parentNode.dataset.index + ".")
        }
    }

    $('.lesson-name').on('click', function () {
        toggleLessonName(this)
    })
    $('#timeRangeCheckbox').on('change', function () {
        $('.lesson-name').each(function (index, element) {
            toggleLessonName(element)
        })
    })
</script>
</html>


