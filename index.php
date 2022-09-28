<?php
include 'vendor/autoload.php';
include 'functions.php';
$timetable = getData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
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
    <form action="calendarView.php" class="col-md-4 col-xs-12 mx-auto">
        <h4 class="text-primary">Пока только для бакалавров</h4>
        <h4 class="text-primary">Для магистров не подходит</h4>
        <div class="mb-3 col-12">
            <label for="group-select" class="text-light">Выберите группу</label>
            <select name="group" id="group-select"></select>
        </div>
        <div class="mb-3 col-12">
            <label for="professor-select" class="text-light">Выберите преподавателя</label>
            <select name="professor" id="professor-select"></select>
        </div>
        <button class="btn btn-primary">Перейти к расписанию</button>
    </form>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/jquery.easing.js"></script>
<script src="dist/js/bsBreakPointDriver.js"></script>
<script src="dist/js/selection.js"></script>
<script>
    let config = {
        synchronizeSelectors: true
    }
    const groupSelector = Selector.generate(document.getElementById('group-select'), config)
    const professorSelector = Selector.generate(document.getElementById('professor-select'), config)
    groupSelector.setOnItemClicked(function (data, object) {
        console.log("GROUP ON ITEM CLICKED", data, groupSelector.getSelection(), object)
        return false;
    });
    professorSelector.setOnItemClicked(function (data, object) {
        console.log("PROFESSOR ON ITEM CLICKED", data, professorSelector.getSelection(), object)
        return false;
    })

    $.getJSON('getProfessors.php', function (data) {
        professorSelector.fillData(data)
        console.log(data)
    })
    $.getJSON('getGroups.php', function (data) {
        groupSelector.fillData(data)
        console.log(data)
    })
</script>
</html>


