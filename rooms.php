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
    <title>Календарь</title>
</head>
<body class="container py-2">
<?php
try {
    echo getBlade()->run("roomsPage", [
        "timetable" => getPreparedTimetable()->groupBy('dayDate')
    ]);
} catch (Exception $e) {
}
?>
</body>
<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/lodash.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    $(document).on('ready', function () {
        window.viewType = document.querySelector("[name=view-selector]:checked").id
    })

    function lessonItemMouseLeaved() {
        $(this).removeClass('hovered');
    }

    function lessonItemMouseFocused() {
        const currentRoom = this;
        this.classList.remove('hovered');
        currentRoom.classList.toggle('hovered');
        let css;
        if (getBreakPoint() === "xs") {
            css = {
                'width': $('.day-lines-container').width() - 18,
                left: 0 - currentRoom.offsetLeft + 5
            }
        } else {
            css = {
                'max-width': $('.day-lines-container').width() - 18,
                left: 0 - currentRoom.offsetLeft + 5
            }
        }
        $(currentRoom).find('.info').css(css)
    }

    function processTimetable(data, viewMode = "rooms", splitLessons = true) {
        console.log(data)
        window.lessonsTimetable = data
        generateLines(
            $('#roomsGrid').removeClass('loading'),
            (urlParams.month !== undefined) ? parseInt(urlParams.month) - 1 : new Date().getMonth(),
            (urlParams.year !== undefined) ? parseInt(urlParams.year) : new Date().getFullYear(),
            viewMode,
            splitLessons
        );
        $('.room')
            .on("mouseleave", lessonItemMouseLeaved)
            .on("click mouseenter", lessonItemMouseFocused)
    }

    const urlParams = getUrlParamsObject();
    $.getJSON('api.php/getRoomDistribute', urlParams).done(processTimetable).fail(responseFail)
    $('.split-toggle').on('click', function () {
        $('#roomsGrid').empty()
        console.log(this.id, this.checked)
        processTimetable(lessonsTimetable, $('.view-type-selector:checked').attr('id'), this.checked)

    })
    $('.view-type-selector').on('click', function () {
        $('#roomsGrid').empty()
        console.log(this.id, this.checked)
        processTimetable(lessonsTimetable, this.id, $('.split-toggle').get(0).checked)
    })
</script>
</html>


