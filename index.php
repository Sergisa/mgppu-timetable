<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="dist/css/style.css">
    <title>Главная</title>
</head>
<body class="container p-4">
<div class="row align-content-between">
    <form action="calendarView.php" class="mb-3 col-12 col-md-5 col-sm-6 col-xs-12 mx-auto d-inline">
        <label for="group-select" class="text-light fs-4">Расписание группы</label>
        <div class="mb-3 col-12 d-flex align-items-center justify-content-between">
            <select name="group" id="group-select" class="d-none"></select>
            <button class="btn btn-primary">Перейти</button>
        </div>
    </form>
</div>
<div class="row align-content-between">
    <form action="calendarView.php" class="mb-3 col-12 col-md-5 col-sm-6 col-xs-12 mx-auto">
        <label for="professor-select" class="text-light fs-4">Расписание преподавателя</label>
        <div class="mb-3 col-12 d-flex align-items-center justify-content-between">
            <select name="professor" id="professor-select" class="d-none"></select>
            <button class="btn btn-primary">Перейти</button>
        </div>
    </form>
</div>
<div class="row align-content-between">
    <form action="rooms.php" class="col-12 col-md-5 col-sm-6 col-xs-12 mx-auto">
        <label for="building-select" class="text-light fs-4">Расписание кабинетов</label>
        <div class="mb-3 col-12 d-flex align-items-center justify-content-between">
            <select name="building" id="building-select" class="d-none"></select>
            <button class="btn btn-primary">Перейти</button>
        </div>
    </form>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    let config = {
        synchronizeSelectors: true,
        initialDisabled: true,
        classList: "flex-grow-1 me-2"
    }
    const groupSelector = Selector.generate(document.getElementById('group-select'), {
        ...config,
        placeholder: "Выберите группу"
    })
    const professorSelector = Selector.generate(document.getElementById('professor-select'), {
        ...config,
        placeholder: "Выберите преподавателя"
    })
    const buildingSelector = Selector.generate(document.getElementById('building-select'), {
        ...config,
        placeholder: "Выберите адрес"
    })
    $.getJSON('api.php/getProfessors', function (data) {
        professorSelector.fillData(data)
        professorSelector.setEnabled()
    }).fail(function (error) {
        console.warn(error.responseText)
    })
    $.getJSON('api.php/getBuildings', function (data) {
        buildingSelector.fillData(data)
        buildingSelector.setEnabled()
    }).fail(function (error) {
        console.warn(error.responseText)
    })
    $.getJSON('api.php/getGroups', function (data) {
        groupSelector.fillData(data)
        groupSelector.setEnabled()
    }).fail(function (error) {
        console.warn(error.responseText)
    })
    $('form').submit(function (event) {
        if ($(this).find('select').val() === null) {
            event.preventDefault();
        }
    })
</script>
</html>


