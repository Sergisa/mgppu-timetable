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
    <title>Document</title>
</head>
<body class="container">
<div class="row">
    <div class="col-md-12">
        <div class="fs-5">
            Скачать всё рассписание для
            <div class="dropdown d-inline-flex align-items-baseline" id="firstDropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="firstDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Выберите опцию
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a id="group" class="dropdown-item" href="#">Группы</a></li>
                    <li><a id="coords" class="dropdown-item" href="#">Территории</a></li>
                    <li><a id="teacher" class="dropdown-item" href="#">Преподавателя</a></li>
                </ul>
            </div>
            <div class="dropdown d-none align-items-baseline" id="secondDropdown">:
                <button class="btn btn-link dropdown-toggle" type="button" id="secondDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Выберите опцию
                </button>
                <ul class="dropdown-menu" aria-labelledby="coordsDropdownList">
                    <li><a id="group" class="dropdown-item" href="#">Группы</a></li>
                    <li><a id="coords" class="dropdown-item" href="#">Территории</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/bundle.js"></script>
<script>
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
    const firstDropDown = new bootstrap.Dropdown(document.getElementById('firstDropdownToggle'));
    const secondDropDown = new bootstrap.Dropdown(document.getElementById('secondDropdownToggle'));

    function updateBuildings() {
    }

    function updateTeacher() {
    }

    function updateGroups() {
    }

    const dropdownList = [firstDropDown, secondDropDown].map(function (dropDownObject) {
        dropDownObject._menu.addEventListener('click', function (event) {
            event.preventDefault()
            dropDownObject.clearActive()
            event.target.classList.add('active');
            dropDownObject._element.innerHTML = event.target.innerHTML;
            if (dropDownObject.getSelectedItemId() == 'coords') {
                updateBuildings()
            }
            if (dropDownObject.getSelectedItemId() == 'group') {
                updateGroups()
            }
            if (dropDownObject.getSelectedItemId() == 'teacher') {
                updateTeacher();
            }
        })
        return dropDownObject;
    })
</script>
</html>


