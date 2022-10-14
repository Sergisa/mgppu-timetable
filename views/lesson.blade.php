<div class='lesson {{$lessonClassList}}' data-index='{{$lessonIndex}}' data-time='{{$lesson['TimeStart']}}'>
    <div class='lesson-name'>
        <b class='fw-bold'>{{$lessonIndex}}. </b>{{$lessonSign}}
    </div>
    @if (isTeacherDefined())
        <div class='lesson-description'>{{$groupsSign}} {{$courseSign}} {{$type}} <br> {{$lessonAddress}}</div>
    @elseif(isGroupDefined())
        <div class='lesson-description'>{{$teacherSign}} {{$lessonAddress}} {{$type}}</div>
    @endif
</div>
