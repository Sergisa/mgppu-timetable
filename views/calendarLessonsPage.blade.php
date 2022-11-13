<div class="row">
    <div class="col-12 col-md-8">
        @include('header')
        <div class="calendar p-1 loading" id="monthGrid"></div>
    </div>
    <div id="listDays" class="col-12 col-md-4">
        <div class="menu row justify-content-around">
            <div class="form-check col-auto">
                <input class="form-check-input" type="checkbox" value="" id="timeRangeCheckbox">
                <label class="form-check-label" for="timeRangeCheckbox">
                    Показывать время
                </label>
            </div>
            <!--<div class="col-auto">
                <i class="bi bi-gear" role="button"></i>
            </div>-->
        </div>
        @include ('dayList')
    </div>
</div>
