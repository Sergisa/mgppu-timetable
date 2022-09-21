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
        <label for="group-select" class="text-light">Выберите группу</label>
        <select name="group" id="group-select">
            <option value="ee">21ИТ-ПИ(б/о)ПИП-1</option>
        </select>
        <label for="professor-select"></label>
        <select name="professor" id="professor-select">
            <option value="ee">21ИТ-ПИ(б/о)ПИП-1</option>
        </select>
        <button class="btn btn-primary">Перейти к расписанию</button>
    </form>

    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown button
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
    </div>

</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/jquery.easing.js"></script>
<script src="dist/js/bsBreakPointDriver.js"></script>
<script src="dist/js/selection.js"></script>
<script>
    $(document).on('ready', function () {
    })
    const groupSelector = Selector.generate(document.getElementById('group-select'))
    const professorSelector = Selector.generate(document.getElementById('professor-select'))
    groupSelector.setOnItemClicked(function (data, object) {
        console.log("GROUP ON ITEM CLICKED", data, groupSelector.getSelection(), object)
        return false;
    });
    professorSelector.setOnItemClicked(function (data, object) {
        console.log("PROFESSOR ON ITEM CLICKED", data, professorSelector.getSelection(), object)
        return false;
    })

    $.getJSON('data/professors.json', function (data) {
        professorSelector.fillData(data)
        console.log(data)
    })
    $.getJSON('data/groups.json', function (data) {
        groupSelector.fillData(data)
        console.log(data)
    })
</script>
</html>


