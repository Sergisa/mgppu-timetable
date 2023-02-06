<div class="row">
    <div class="col-auto mx-auto">
        <div class="month-title d-flex align-items-baseline mb-2">
            <a href="/" tabindex="1">
                <i class="bi bi-backspace fs-1"></i>
            </a>
            <div>
                <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
                <p class="lead d-md-inline m-0 subtitle ms-2">
                    {{getBuildingById($_GET['building'])}}
                </p>
            </div>
            <a href="?{{getPreviousMonthLink()}}" class="d-inline ms-auto">
                <i class="bi bi-arrow-left-square fs-1"></i>
            </a>
            <a href="?{{getNextMonthLink()}}" class="d-inline">
                <i class="bi bi-arrow-right-square fs-1"></i>
            </a>
        </div>
        <div class="room-list p-1 loading" id="roomsGrid"></div>
    </div>
</div>
