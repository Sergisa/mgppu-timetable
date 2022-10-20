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
<body class="container">
<div class="row">
    <form action="calendarView.php" class="col-md-4 col-xs-12 mx-auto">
        <h4 class="text-primary">Выберите что-нибудь</h4>
        <div class="mb-3 col-12">
            <label for="group-select" class="text-light">Либо группу</label>
            <select name="group" id="group-select" class="d-none"></select>
        </div>
        <div class="mb-3 col-12">
            <label for="professor-select" class="text-light">Либо преподавателя</label>
            <select name="professor" id="professor-select" class="d-none"></select>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-primary fs-5">Или и то и другое</span>
            <button class="btn btn-primary">Перейти к расписанию</button>
        </div>
        <h5 class="mt-3 fw-lighter">Если у вас случились проблемы при использовании данного сайта можно написать
            <a class="btn-link" href="https://github.com/Sergisa/mgppu-timetable/issues">сюда</a>
        </h5>
    </form>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    let config = {
        synchronizeSelectors: true,
        initialDisabled: true
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

    $.getJSON('api.php/getProfessors', function (data) {
        professorSelector.fillData(data)
        professorSelector.setEnabled()
    })
    $.getJSON('api.php/getGroups', function (data) {
        groupSelector.fillData(data)
        groupSelector.setEnabled()
    })
</script>
</html>


