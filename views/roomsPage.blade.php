<div class="row">
    <div class="col-12 col-md-8">
        <div class="month-title d-flex align-items-baseline mb-2">
            <a href="/" tabindex="1">
                <i class="bi bi-backspace fs-1"></i>
            </a>
            <div>
                <h1 class="fw-bolder d-inline m-0 ms-2">{{getActiveDay()}}.{{getActiveMonth()}} {{getDays()[(int)getActiveDay()]}}</h1>
            </div>
        </div>
        <div class="calendar p-1 loading" id="roomsGrid"></div>
    </div>

</div>
