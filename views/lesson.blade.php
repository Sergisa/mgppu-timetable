<div class='lesson {{$lessonClassList}}'
     data-index='{{$lessonIndex}}'
     data-time='{{$lesson['TimeStart']}}'
     data-range='{{substr($lesson['TimeStart'],0,-3)}} - {{substr($lesson['TimeEnd'],0,-3)}}'>
    <div class='lesson-name'>
        <b class='lesson-index'>{{$lessonIndex}}.</b> {{$lessonSign}}
        <span class="room">({{$lesson['Coords']['room']['index']}} каб.)</span>
        @if (isSessionPart($lesson))
            ({{ $type }})
        @endif
    </div>
    @if (isTeacherDefined())
        <div class='lesson-description'>{{$groupsSign}} {{$courseSign}} {{$type}} <br> {{$lessonAddress}}</div>
    @elseif(isGroupDefined())
        <div class='lesson-description'>{{$teacherSign}} {{$lessonAddress}} {{$type}}</div>
    @endif
</div>
