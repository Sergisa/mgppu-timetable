@forelse($timetable as $date => $lessons)
    @include('day',[
    'date' => $date,
    'lessons' => $lessons->sortByDate('TimeStart')
    ])
@empty
    <h2 class='text-primary text-center mt-4'>Нет пар</h2>
@endforelse

