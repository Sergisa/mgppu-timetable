<span class='date me-1'>{{ convertDate('d.m', $date) }} {{ getDayName($date) }}</span>
<ul class="list-group list-group-flush bg-opacity-100">
    <li class='list-group-item' data-date='{{$date}}'>
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
</ul>
