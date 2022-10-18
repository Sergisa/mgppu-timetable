<div class="month-title d-md-inline">
    <a href="/" tabindex="1">
        <i class="bi bi-backspace fs-1"></i>
    </a>
    <h1 class="fw-bolder d-inline">{{getMonths()[(int)getActiveMonth()]}}</h1>
    <a href="?{{getNextMonthLink()}}" class="d-inline">
        <i class="bi bi-arrow-right-square fs-1 float-end"></i>
    </a>
    <a href="?{{getPreviousMonthLink()}}" class="d-inline">
        <i class="bi bi-arrow-left-square fs-1 float-end"></i>
    </a>
</div>
@if (isTeacherDefined())
    <p class="lead d-md-inline m-0 mb-md-3 subtitle">
        {{getTeacherById($_GET['professor'])}}
    </p>
@endif
@if(isGroupDefined())
    <p class="lead text-primary d-md-inline m-0 mb-md-3 subtitle">
        {{getGroupById($_GET['group'])}}
    </p>
@endif
