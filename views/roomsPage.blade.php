<div class="row">
    <div class="col-auto mx-auto">
        <div class="month-title d-flex align-items-baseline mb-2">
            <a href="/" tabindex="1">
                <i class="bi bi-backspace fs-1"></i>
            </a>
            <div>
                <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
                <p class="lead"></p>
            </div>
        </div>
        <div class="room-list p-1 loading" id="roomsGrid"></div>
    </div>

</div>
