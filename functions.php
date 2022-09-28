<?php

use Illuminate\Support\Collection;

Collection::macro('sortByDate', function (string $column = 'created_at', bool $descending = false) {
    return $this->sortBy(function ($datum) use ($column) {
        return strtotime(((object)$datum)->$column);
    }, SORT_REGULAR, $descending);
});
function getMonths(): array
{
    return [
        1 => "Январь",
        2 => "Февраль",
        3 => "Март",
        4 => "Апрель",
        5 => "Май",
        6 => "Июнь",
        7 => "Июль",
        8 => "Август",
        9 => "Сентябрь",
        10 => "Октябрь",
        11 => "Ноябрь",
        12 => "Декабрь"
    ];
}

function getFileData($fileName)
{
    $myfile = fopen("data/$fileName", "r") or die("Unable to open file!");
    $file = fread($myfile, filesize("data/$fileName"));
    fclose($myfile);
    return collect(json_decode($file, true));
}

function getGroupById($id)
{
    return getFileData('groups.json')[$id];
}

function getTeacherById($id)
{
    return getFileData('professors.json')[$id];
}

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

function isTeacherTimetable($get = null): bool
{
    if ($get == null) {
        $get = $_GET;
    }
    return array_key_exists('professor', $get) || (!array_key_exists('group', $get));
}

function isGroupTimetable($get = null): bool
{
    if ($get == null) {
        $get = $_GET;
    }
    return array_key_exists('group', $get) && !array_key_exists('professor', $get);
}

function getTeacherSignature($lesson): string
{
    if (is_null($lesson['Teacher']['name'])) return "";
    $split = explode($lesson['Teacher']['name'], " ");
    return $lesson['Teacher']['name'];
//    return $split[0] ." ". substr($split[1], 0, 1) ." ". substr($split[2], 0, 1);
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
    return "(" . implode(', ', $groupCodeList->toArray()) . ")";
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

function getActiveMonth()
{
    return array_key_exists('month', $_GET) ? $_GET['month'] : date('m');
}

function getNextMonth()
{
    return (getActiveMonth() + 1) >= 10 ? (getActiveMonth() + 1) : '0' . (getActiveMonth() + 1);
}

function getPreviousMonth()
{
    return (getActiveMonth() - 1) >= 10 ? (getActiveMonth() - 1) : '0' . (getActiveMonth() - 1);
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
    $timetable = groupCollapsing($timetable);//FIXME: Общая психология пропала для преподавателя
    $timetable = $timetable
        //->where("TeacherFIO", "Исаков Сергей Сергеевич")
        //->where("Department.code", "ИТ")
        ->filter(function ($lesson) {
            return str_contains($lesson['dayDate'], "." . getActiveMonth() . ".");
        })
        ->filter(function ($lesson) {
            return !array_key_exists('group', $_GET) || $lesson['Group']['id'] == $_GET['group'];
        })
        ->filter(function ($lesson) {
            if (array_key_exists('professor', $_GET)) {
                return ($lesson['Teacher']['id'] == $_GET['professor']);
            } elseif (!array_key_exists('group', $_GET)) {
                return ($lesson['Teacher']['name'] == "Исаков Сергей Сергеевич");
            } else {
                return true;
            }
        });
    return collapseSimilarities($timetable)
        ->sortBy(['Number'])
        ->sortByDate('dayDate')
        ->groupBy('dayDate');
}
