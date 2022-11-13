<div class="row">
    <div class="col-12 col-md-8">
        <div class="month-title d-flex align-items-baseline mb-2">
            <a href="/" tabindex="1">
                <i class="bi bi-backspace fs-1"></i>
            </a>
            <div>
                <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
            </div>
            <a href="?{{getPreviousMonthLink()}}" class="d-inline ms-auto">
                <i class="bi bi-arrow-left-square fs-1"></i>
            </a>
            <a href="?{{getNextMonthLink()}}" class="d-inline">
                <i class="bi bi-arrow-right-square fs-1"></i>
            </a>
        </div>
        <div class="calendar p-1 loading" id="monthGrid"></div>
    </div>
    <div id="listDays" class="col-12 col-md-4">
        <div class="menu row justify-content-around">
            <div class="form-check col-auto">
                <input class="form-check-input" type="checkbox" value="" id="timeRangeCheckbox">
                <label class="form-check-label" for="timeRangeCheckbox">Показывать время</label>
            </div>
        </div>
        {{--@include ('dayList')--}}
    </div>
</div>
<pre>{{$timetable->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)}}</pre>
