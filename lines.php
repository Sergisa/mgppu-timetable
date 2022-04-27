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
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        #main {
            scrollbar-base-color: red;
        }

        #main {
            height: 100vh;
            overflow-y: scroll;
        }
    </style>
</head>
<body class="container">

<div class="row justify-content-between align-items-center">
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
    <div class="line">
        <div class="head">
            <div class="day">Суббота</div>
            <div class="day">21 мая</div>
        </div>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function fillDay(line, from, to) {
        line.css({
            'top': `${(100 / 5) * from}%`,
            'bottom': `${100 / 5 * from - to}%`
        })
    }

    $('.line').prepend('<span class="overlay"></span>')

    fillDay($('.line:eq(1) .overlay'), 2, 5);
    fillDay($('.line:eq(4) .overlay'), 1, 4);
</script>
</html>


