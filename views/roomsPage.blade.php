<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="month-title d-flex align-items-baseline mb-2">
            <a href="/" tabindex="1">
                <i class="bi bi-backspace fs-1"></i>
            </a>
            <div class="w-100">
                <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
                <div class="d-inline-flex d-lg-none float-end">
                    <a href="?{{getPreviousMonthLink()}}" class="d-inline left-arrow">
                        @include('arrow')
                    </a>
                    <a href="?{{getNextMonthLink()}}" class="d-inline right-arrow">
                        @include('arrow')
                    </a>
                </div>
                <p class="lead d-md-inline m-0 subtitle ms-2">
                    {{getBuildingById($_GET['building'])}}
                </p>
            </div>
            <a href="?{{getPreviousMonthLink()}}" class="d-md-inline d-none ms-auto left-arrow">
                @include('arrow')
            </a>
            <a href="?{{getNextMonthLink()}}" class="d-md-inline d-none right-arrow">
                @include('arrow')
            </a>
        </div>
        @include('menu',[
                'timeShow'=>false,
                'toggle'=>true,
                'current' => true
            ])
        <div class="room-list p-1 loading" id="roomsGrid"></div>
    </div>
</div>
