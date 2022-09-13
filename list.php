<?php
include 'vendor/autoload.php';
include 'functions.php';
?>
<!doctype html>
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
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="container">
<div class="row">
    <div class="col-8 d-flex matrix row justify-content-between align-items-center flex-nowrap overflow-auto">
        <div class="line">
            <div class="head">
                <div class="day">Суббота</div>
                <div class="day">21 мая</div>
            </div>
            <div class="lesson">Проектный практикум в предметной области психология</div>
            <div class="lesson">Информационные системы и базы данных</div>
            <div class="lesson">Проектрование информационных систем</div>
        </div>
        <div class="line">
            <div class="head">
                <div class="day">Суббота</div>
                <div class="day">21 мая</div>
            </div>
        </div>
        <div class="line">
            <div class="head">
                <div class="day">Суббота</div>
                <div class="day">21 мая</div>
            </div>
        </div>
        <div class="line">
            <div class="head">
                <div class="day">Суббота</div>
                <div class="day">21 мая</div>
            </div>
        </div>
        <div class="line">
            <div class="head">
                <div class="day">Суббота</div>
                <div class="day">21 мая</div>
            </div>
        </div>
    </div>
    <div id="listDays" class="col-4">
        <ul class="list-group list-group-flush bg-opacity-100">
            <li class="list-group-item">
                <span class="date">20.12</span>
                Проектный практикум в предметной области психология
            </li>
            <li class="list-group-item">A second item</li>
            <li class="list-group-item">A third item</li>
            <li class="list-group-item">A fourth item</li>
            <li class="list-group-item">And a fifth one</li>
        </ul>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const lines = $('.line');
    const lessonAmount = 5;
    const topHeight = 8;

    function signum(x) {
        return (x === 0) ? 0 : Math.abs(x) / x;
    }

    function makeGridLines() {
        for (let i = 1; i <= 4; i++) {
            lines.append(`<span class="timeLine ${i}"></span>`);
        }
    }

    function fillDay(line, from, to) {
        const lessonHeight = (100 - topHeight) / lessonAmount;
        const top = (topHeight + lessonHeight * from) * signum(from - 1);
        const range = to - from;
        //FIXME: Signum not working on height calculation
        const rangeHeight = (range * lessonHeight) + 8 - (topHeight * signum(from - 1));
        line.html(`${from}-${to}`)

        line.css({
            'top': `${top}%`,
            'height': `${rangeHeight}%`
        })
    }

    lines.prepend('<span class="overlay"></span>')
    $('.line .head').click(function () {
        $('.line.active').removeClass('active');
        $(this).parent().addClass('active');
    })

    fillDay($('.line:eq(0) .overlay'), 1, 3);
    fillDay($('.line:eq(1) .overlay'), 2, 5);
    fillDay($('.line:eq(4) .overlay'), 1, 4);
    fillDay($('.line:eq(3) .overlay'), 2, 3);
    makeGridLines()
    //draw(document.getElementById('timeLines'))
</script>
</html>


