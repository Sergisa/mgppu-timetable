<ul class="list-group list-group-flush bg-opacity-100">
    @forelse($timetable as $date => $lessons)
        @include('day',[
        'date' => $date,
        'lessons' => $lessons
        ])
    @empty
        <h2 class='text-primary text-center mt-4'>Нет пар</h2>
    @endforelse
</ul>
