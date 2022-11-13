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
    <div class='lesson-description'>
        @if (isTeacherDefined())
            {{$groupsSign}} {{$courseSign}} <br> {{$type}} <br> {{$lessonAddress}}
        @elseif(isGroupDefined())
            {{$teacherSign}} {{$type}}<br>{{$lessonAddress}}
        @endif
        @if (isSessionPart($lesson))
            <span class='type session me-1'>{{getLessonTypeSignature($lesson["finalCheckTypeID"])}}</span>
        @else
            <span class='type me-1'>{{getLessonTypeSignature($lesson["TypeID"])}}</span>
        @endif
    </div>

</div>
