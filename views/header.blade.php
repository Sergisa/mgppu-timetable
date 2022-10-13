<h1 class="fw-bolder month-title text-primary d-md-inline">
    <a href="/">
        <i class="bi bi-backspace"></i>
    </a>
    {{getMonths()[(int)getActiveMonth()]}}
    <a href="?{{getNextMonthLink()}}" class="{{getActiveMonth() < 12 ? " d-inline" : 'invisible'}}">
        <i class="bi bi-arrow-right-square fs-1 float-end"></i>
    </a>
    <a href="?{{getPreviousMonthLink()}}" class="{{getActiveMonth() > 1 ? " d-inline" : 'invisible'}}">
        <i class="bi bi-arrow-left-square fs-1 float-end"></i>
    </a>
</h1>
<p class="lead text-primary d-md-inline m-0 mb-md-3 subtitle">
    @if (isTeacherDefined())
        {{getTeacherById($_GET['professor'])}}
    @endif
</p>
<p class="lead text-primary d-md-inline m-0 mb-md-3 subtitle">
    @if(isGroupDefined())
        {{getGroupById($_GET['group'])}}
    @endif
</p>
