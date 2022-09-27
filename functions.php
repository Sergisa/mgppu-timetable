<?php

use Illuminate\Support\Collection;

Collection::macro('sortByDate', function (string $column = 'created_at', bool $descending = false) {
    return $this->sortBy(function ($datum) use ($column) {
        return strtotime(((object)$datum)->$column);
    }, SORT_REGULAR, $descending);
});
function convertDate($pattern, $date): string
{
    return date($pattern, strtotime($date));
}

function getLessonSignature($lesson): string
{
    return $lesson['Discipline'] . ' ';
}

function getLessonTypeSignature($type): string
{
    return mb_substr($type == "Практические занятия" ? "Семинар" : "Лекции", 0, 1);
}

function getGroupYear($group): string
{
    preg_match_all('/(\d{2})([А-Я]{2})-([А-Я]+)\((.+)\)([А-Я]+)-(\d)/u', $group, $m);
    return $m[1][0];
}

function getCourseNumber($group, $currentMonth = null, $currentYear = null): string
{
    $activeMonth = $currentMonth ?? date('m');
    $activeYear = $currentYear ?? date('y'); // y: 22 Y: 2022
    $course = $activeYear - getGroupYear($group);
    if ($activeMonth >= 9 && $activeMonth <= 12) $course++;
    return "{$course} курс";
}

function getLessonIndex($lesson): string
{
    preg_match_all('/(\d) *пара/ui', $lesson['Number'], $index);
    return $index[1][0];
}

function getGroupsSignature($lesson): string
{
    $groupCodeList = collect($lesson['Group'])->pluck('name')->map(function ($code) {
        preg_match_all('/(\d{2}[А-Я]{2})-([А-Я]+)\((.+)\)([А-Я]+)-(\d)/u', $code, $m);
        return $m[2][0];
    });
    return ($groupCodeList->count() > 1) ? "(" . implode(', ', $groupCodeList->toArray()) . ")" : "";
}

function collapseSimilarities(Collection $timetable): Collection
{
    return $timetable->map(function ($lesson) use ($timetable) {
        $similarities = $timetable->filter(function ($tmt) use ($lesson) {
            return $tmt['dayDate'] == $lesson['dayDate']
                && $tmt['Group']['id'] != $lesson['Group']['id']
                && $tmt['DisciplineID'] == $lesson['DisciplineID']
                && $tmt['Number'] == $lesson['Number'];
        });
        if ($similarities->count() > 0) {
            $lesson['Group'] = array_merge([$lesson['Group']], $similarities->pluck('Group')->unique()->toArray());
        } else {
            $lesson['Group'] = [$lesson['Group']];
        }
        return $lesson;
    })->unique(function ($item) {
        return $item['dayDate'] . $item['Number'];
    });
}

function groupCollapsing($timetable): Collection
{
    return $timetable->map(function ($item) use ($timetable) {
        $newObj = collect($item)->prepend([
            'building' => [
                "id" => $item['BuildingID'],
                "name" => $item['Building']
            ],
            'floor' => [
                "id" => $item['FloorID'],
                "name" => $item['Floor']
            ],
            'room' => [
                "index" => $item['Room'],
                "id" => $item['RoomID']
            ]
        ], "Coords")->prepend([
            'id' => $item['TeacherID'],
            'name' => $item['TeacherFIO'],
        ], "Teacher")->prepend([
            'id' => $item['GroupID'],
            'name' => $item['GroupCode'],
        ], "Group")->prepend([
            'id' => $item['DepartmentID'],
            'name' => $item['DepartmentName'],
            'code' => $item['DepartmentCode'],
        ], "Department")->filter(function ($item, $key) {
            return !in_array($key, [
                "FloorID",
                "Floor",
                "Building",
                "BuildingID",
                "GroupID",
                "Room",
                "RoomID",
                "GroupCode",
                "DepartmentName",
                "DepartmentCode"
            ]);
        });

        return $newObj->toArray();
    });
}

/**
 * @return Collection
 */
function getData(): Collection
{
    ini_set('memory_limit', '-1');
    $myfile = fopen("data/Timetable2022.json", "r") or die("Unable to open file!");
    $file = fread($myfile, filesize("data/Timetable2022.json"));
    fclose($myfile);
    $timetable = collect(json_decode($file, true));
    $timetable = groupCollapsing($timetable);
    $timetable = $timetable
        ->where("TeacherFIO", "Исаков Сергей Сергеевич")
        ->where("Department.code", "ИТ");
    return collapseSimilarities($timetable)
        ->sortBy(['Number'])
        ->sortByDate('dayDate')
        ->filter(function ($lesson) {
            return str_contains($lesson['dayDate'], "." . date('m') . ".");
        })
        ->groupBy('dayDate');
}
