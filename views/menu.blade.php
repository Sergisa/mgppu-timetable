<div class="menu row justify-content-around px-4">
    @if($timeShow)
        <div class="form-check col-auto">
            <input class="form-check-input" type="checkbox" value="" id="timeRangeCheckbox">
            <label class="form-check-label" for="timeRangeCheckbox">
                Время
            </label>
        </div>

    @endif
    @if($toggle)

        <div class="btn-group btn-group-sm col-auto mb-1" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check view-type-selector" name="view-selector" id="groups"
                   autocomplete="off">
            <label class="btn btn-outline-primary" for="groups">Группы</label>

            <input type="radio" class="btn-check view-type-selector" name="view-selector" id="professors"
                   autocomplete="off">
            <label class="btn btn-outline-primary" for="professors">Преподаватели</label>

            <input type="radio" class="btn-check view-type-selector" name="view-selector" id="rooms" autocomplete="off"
                   checked>
            <label class="btn btn-outline-primary" for="rooms">Комнаты</label>
        </div>
        <div class="btn-group btn-group-sm col-auto mb-1" role="group" aria-label="Basic radio toggle button group">
            <input type="checkbox" class="btn-check split-toggle" name="showLessons" id="showLessons"
                   autocomplete="off" checked>
            <label class="btn btn-outline-primary" for="showLessons">По парам</label>
        </div>
    @endif
    @if($current)
        <div class="col-auto ms-auto">
            <button type="button" class="btn btn-sm btn-outline-primary" id="mark_nearest">Актуальное</button>
        </div>
        @endif
</div>