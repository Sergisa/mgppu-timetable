<span class='date-header'>{{ convertDate('d.m', $date) }} {{ getDayName($date) }}</span>
<div class="bg-opacity-100 day" data-date='{{$date}}'>
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
        'lessonClassList' => isSessionPart($lesson) ? ' session' : ''
        ])
    @endforeach
</div>
