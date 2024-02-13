<div class='date-header'>
    <div class="date-text">
        {{ convertDate('d.m', $date) }}
        {{ getDayName($date) }}
    </div>
    <span class="interval"></span>
    <div class="date-header__menu-icon" data-bs-toggle="dropdown" aria-expanded="false">
        @include('dots')
    </div>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Сводка на этот момент</a></li>
        <li><a class="dropdown-item" href="#">Редактировать запись</a></li>
        <li><a class="dropdown-item" href="#">Супер секретная функция</a></li>
    </ul>
</div>
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
