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
    </style>
</head>
<body class="container">
<div class="row">
    <form action="">
        <select name="group" id="group-select">
            <option value="ee">21ИТ-ПИ(б/о)ПИП-1</option>
        </select>
        <div class="select" id="groupSelect">
            <div class="selection">Выберите вариант</div>
            <div class="variant-list"></div>
        </div>
        <button class="btn btn-primary">Перейти к расписанию</button>
    </form>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/jquery.easing.js"></script>
<script src="dist/js/selection.js"></script>
<script>
    $(document).ready(function () {
    })
    const groupSelector = new Selector($('#groupSelect'))
    groupSelector.fillData({
        "d": '21ИТ-ПИ(б/о)ПИП-1',
        "s": '20ИТ-ПИ(б/о)ПИП-1',
        "a": '19ИТ-МО(б/о)ИСБД-1'
    })
    groupSelector.onItemClicked(function (data) {
        console.log(data)
    })

    $('.variant').click(function () {
        $('.variant-list .variant.active').removeClass('active')
        $(this).addClass('active')
    })
    $('#groupSelect').click(function () {
        $(this).find('.variant-list').toggleClass('active')
    })
</script>
</html>


