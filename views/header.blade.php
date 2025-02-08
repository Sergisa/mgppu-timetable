<div class="month-title d-flex align-items-baseline mb-2">
    <a href="/" tabindex="1">
        <i class="bi bi-backspace fs-1"></i>
    </a>
    <div class="w-100">
        <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
        <div class="d-inline-flex d-md-none float-end">
            <a href="?{{getPreviousMonthLink()}}" class="d-inline left-arrow">@include('arrow')</a>
            <a href="?{{getNextMonthLink()}}" class="d-inline right-arrow">@include('arrow')</a>
        </div>
        <div class="d-inline-flex align-items-center">
            @if (isTeacherDefined())
                <p class="lead d-md-inline m-0 subtitle ms-2">
                    {{getTeacherById($_GET['professor'])}}
                </p>
            @endif
            @if(isGroupDefined())
                <p class="lead d-md-inline m-0 subtitle ms-2">
                    {{getGroupById($_GET['group'])}}
                </p>
            @endif
            {{--<button class="ms-2 btn btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-gear"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item d-flex align-items-center" data-bs-toggle="button" href="#">Показывать группы</a></li>
                <li><a class="dropdown-item d-flex align-items-center" data-bs-toggle="button" href="#">Показывать дни недели</a></li>
            </ul>--}}
        </div>
    </div>
    <a href="?{{getPreviousMonthLink()}}" class="d-md-inline d-none ms-auto left-arrow">
        @include('arrow')
    </a>
    <a href="?{{getNextMonthLink()}}" class="d-md-inline d-none right-arrow">
        @include('arrow')
    </a>
</div>

