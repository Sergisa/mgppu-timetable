<div class="month-title d-flex align-items-baseline mb-2">
    <a href="/" tabindex="1">
        <i class="bi bi-backspace fs-1"></i>
    </a>
    <div class="w-100">
        <h1 class="fw-bolder d-inline m-0 ms-2">{{getMonths()[(int)getActiveMonth()]}}</h1>
        <div class="d-inline-flex d-md-none float-end">
            <a href="?{{getPreviousMonthLink()}}" class="d-inline left-arrow"></a>
            <a href="?{{getNextMonthLink()}}" class="d-inline right-arrow"></a>
        </div>
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
    <a href="?{{getPreviousMonthLink()}}" class="d-md-inline d-none ms-auto left-arrow"></a>
    <a href="?{{getNextMonthLink()}}" class="d-md-inline d-none right-arrow"></a>
</div>

