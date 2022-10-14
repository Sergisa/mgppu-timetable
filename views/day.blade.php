<li class='list-group-item' data-date='{{$date}}'>
    <div class='labels'>
        <span class='date me-1'>{{ convertDate('d.m', $date) }}</span>
        @foreach ($lessons as $lesson)
            @if (isSessionPart($lesson))
                <span class='type session me-1'>{{getLessonTypeSignature($lesson["finalCheckTypeID"])}}</span>
            @else
                <span class='type me-1'>{{getLessonTypeSignature($lesson["TypeID"])}}</span>
            @endif
        @endforeach
    </div>
    @foreach ($lessons as $lesson)
        @include('lesson',[
        'lesson'=>$lesson,
        'lessonAddress' => $lesson['Coords']['building']['name'],
        'type' => getLessonFullType(isSessionPart($lesson) ? $lesson["finalCheckTypeID"] : $lesson["TypeID"]),
        'lessonSign' => getLessonSignature($lesson),
        'groupsSign' => getGroupsSignature($lesson),
        'courseSign' => getCourseNumber($lesson['Group'][0]['name']),
        'teacherSign' => getTeacherSignature($lesson),
        'lessonIndex' => getLessonIndex($lesson),
        'lessonClassList' => isSessionPart($lesson) ? 'session' : ''
        ])
    @endforeach

</li>
