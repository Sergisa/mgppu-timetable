<div class="month-title d-flex align-items-baseline mb-2">
    <a href="/" tabindex="1">
        <i class="bi bi-backspace fs-1"></i>
    </a>
    <div>
        <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
        @if (isTeacherDefined())
            <p class="lead d-md-inline m-0 subtitle ms-2">
                {{getTeacherById($_GET['professor'])}}
            </p>
        @endif
        @if(isGroupDefined())
            <p class="lead text-primary d-md-inline m-0 subtitle ms-2">
                {{getGroupById($_GET['group'])}}
            </p>
        @endif
    </div>
    <a href="?{{getPreviousMonthLink()}}" class="d-inline ms-auto">
        <i class="bi bi-arrow-left-square fs-1"></i>
    </a>
    <a href="?{{getNextMonthLink()}}" class="d-inline">
        <i class="bi bi-arrow-right-square fs-1"></i>
    </a>
</div>

