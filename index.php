<!DOCTYPE html>
<?php
    include 'vendor/autoload.php';
    include 'functions.php';
?>
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
<body class="container py-4">
    <?php
        try {
            echo getBlade()->run("indexPage");
        } catch (Exception $e) {
        }
    ?>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    let config = {
        synchronizeSelectors: true,
        initialDisabled: true,
        classList: "flex-grow-1 me-md-2 w-100"
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
        appender($('form#professor-form').get(0))
    }).fail(function (error) {
        console.warn(error.responseText)
    })
    $.getJSON('api.php/getBuildings', function (data) {
        buildingSelector.fillData(data)
        buildingSelector.setEnabled()
        appender($('form#building-form').get(0))
    }).fail(function (error) {
        console.warn(error.responseText)
    })
    $.getJSON('api.php/getGroups', function (data) {
        groupSelector.fillData(data)
        groupSelector.setEnabled()
        appender($('form#group-form').get(0))
    }).fail(function (error) {
        console.warn(error.responseText)
    })

    function appendLocalStorage(key, value) {
        var items = {}
        if (localStorage.getItem('lastSelectedItems') !== null) {
            items = JSON.parse(localStorage.getItem('lastSelectedItems'))
        }
        if (!Array.isArray(items[key])) {
            items[key] = []
        }
        if (!items[key].includes(value)) {
            items[key].push(value);
        }
        localStorage.setItem('lastSelectedItems', JSON.stringify(items))
    }

    // <a href="#" class="link-underline-opacity-0 badge text-bg-light">Исаков Сергей Сергеевич</a>
    function appender(form) {
        var nativeSelectObject = $(form).find('select').get(0);
        var parentBox = $(form).find('select').parent().parent()
        var storage = JSON.parse(localStorage.getItem('lastSelectedItems'))
        var getOptionString = (key) => {
            return $(`select option[value="${key}"]`).html()
        }
        if (storage !== null) {
            if (storage[nativeSelectObject.id] !== undefined) {
                for (var element of storage[nativeSelectObject.id]) {
                    parentBox.append(`<a href="${form.action}?${nativeSelectObject.name}=${element}" class="me-2 link-underline-opacity-0 badge fw-medium bg-light">${getOptionString(element)}</a>`)
                }
            }
        }
    }

    $('form').submit(function (event) {
        var selectObject = $(this).find('select')
        if (selectObject.val() === null) {
            event.preventDefault();
        }
        appendLocalStorage(selectObject.attr('id'), selectObject.val())
    })
</script>
</html>


