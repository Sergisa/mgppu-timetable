<div class='{{$lessonClassList}}' data-time='{{$lesson['TimeStart']}}'>
    <div class='lesson-name'>
        <b class='fw-bold'>{{$lessonIndex}}. </b>{{$lessonSign}}
    </div>
    @if (isTeacherDefined())
        <span class='lesson-description'>{{$groupsSign}} {{$courseSign}} {{$lessonAddress}} {{$type}}</span>
    @elseif(isGroupDefined())
        <span class='lesson-description'>{{$teacherSign}} {{$lessonAddress}} {{$type}}</span>
    @endif
</div>
