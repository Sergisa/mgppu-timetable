<div class="menu row justify-content-around px-4">
    @if($timeShow)
        <div class="form-check col-auto">
            <input class="form-check-input" type="checkbox" value="" id="timeRangeCheckbox">
            <label class="form-check-label" for="timeRangeCheckbox">
                Показывать время
            </label>
        </div>

    @endif

    @if($current)
        <div class="col-auto ms-auto">
            <button class="btn btn-sm btn-outline-primary" id="mark_nearest">Актуальное</button>
        </div>
    @endif
</div>