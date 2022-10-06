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
    ini_set('memory_limit', '-1');
    $myfile = fopen("data/$fileName", "r") or die("Unable to open file!");
    $file = fread($myfile, filesize("data/$fileName"));
    fclose($myfile);
    return collect(json_decode($file, true));
}

function getGroupById($id)
{
    return getTimetable()->pluck("Group")->unique()->values()->where('id', '=', $id)->unique()->values()[0]['name'];
}

function getTeacherById($id)
{
    if ($id = "null") $id = null;
    return getTimetable()->pluck("Teacher")->where('id', '=', $id)->unique()->values()[0]['name'];
}

function convertDate($pattern, $date): string
{
    return date($pattern, strtotime($date));
}

function getLessonSignature($lesson): string
{
    return $lesson['Discipline'] . ' ';
}

function getLessonTypeSignature($typeID): ?string
{
    $types = [
        '0x80C9000C295831B711E8A95D5CD1DE1A' => ['Семинар', 'Сем.'],
        '0xAD88005056B76B4C11E6B2C498121654' => ['Лекции', 'Лек.'],
        '0xAD88005056B76B4C11E6B2C49FAB9534' => ['Лабораторные работы', 'Лаб.'],
        '0xAD88005056B76B4C11E6B2C4A65DCFFA' => ['Практические занятия', 'Пр.']
    ];
    return $types[$typeID][1] ?? mb_substr($typeID, 0, 3);
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
    $split = explode(" ", $lesson['Teacher']['name']);
    return $split[0] . " " . mb_substr($split[1], 0, 1) . "." . mb_substr($split[2], 0, 1) . ".";
}

function parseGroupCode($group)
{
    preg_match_all('/(\d{2,})([А-я]+)-([А-я]+)\((.+)\)([А-я]+)-(\d)/u', $group, $m);
    return $m;
}

function getGroupsSignature($lesson): string
{
    $groupCodeList = collect($lesson['Group'])->pluck('name')->map(function ($group) {
        return getGroupSpeciality($group);
    });
    return "(" . implode(', ', $groupCodeList->toArray()) . ")";
}

function getGroupSpeciality($group): string
{
    return parseGroupCode($group)[3][0];
}

function getGroupYear($group): string
{
    return parseGroupCode($group)[1][0];
}

function getCourseNumber($group, $currentMonth = null, $currentYear = null): string
{
    $activeMonth = $currentMonth ?? date('m');
    $activeYear = $currentYear ?? date('y'); // y: 22 Y: 2022
    $course = $activeYear - getGroupYear($group);
    if ($activeMonth >= 9 && $activeMonth <= 12) $course++;
    return "$course курс";
}

function getLessonIndex($lesson): string
{
    if (is_null($lesson['Number'])) {
        return mb_substr($lesson['TimeStart'], 0, 5);
    } else {
        preg_match_all('/(\d) *пара/ui', $lesson['Number'], $index);
        return $index[1][0];
    }
}

function joinParallelLessonsByGroup(Collection $timetable): Collection
{
    return $timetable->map(function ($lesson) use ($timetable) {
        $similarities = $timetable->filter(function ($tmt) use ($lesson) {
            return $tmt['dayDate'] == $lesson['dayDate']
                && $tmt['Group']['id'] != $lesson['Group']['id']
                && $tmt['DisciplineID'] == $lesson['DisciplineID']
                && $tmt['Coords']['room']['id'] == $lesson['Coords']['room']['id']
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

function getNextMonthLink(): string
{
    return http_build_query(array_merge($_GET, ['month' => getNextMonth()]));
}

function getNextMonth()
{
    return (getActiveMonth() + 1) >= 10 ? (getActiveMonth() + 1) : '0' . (getActiveMonth() + 1);
}

function getPreviousMonthLink(): string
{
    return http_build_query(array_merge($_GET, ['month' => getPreviousMonth()]));
}

function getPreviousMonth()
{
    return (getActiveMonth() - 1) >= 10 ? (getActiveMonth() - 1) : '0' . (getActiveMonth() - 1);
}

function collapseDataHierarchically($timetable): Collection
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

function getTimetable(): Collection
{
    $timetable = getFileData("tmtFull.json");
    return collapseDataHierarchically($timetable); //FIXME: Общая психология пропала для препода
}

/**
 * @return Collection
 */
function getData(): Collection
{
    $professor = $_GET['professor'];
    if ($_GET['professor'] == "null") {
        $professor = null;
    }
    return joinParallelLessonsByGroup(getTimetable()
        ->filter(function ($lesson) {
            return str_contains($lesson['dayDate'], "." . getActiveMonth() . ".");
        })
        ->filter(function ($lesson) {
            return !array_key_exists('group', $_GET) || ($lesson['Group']['id'] == $_GET['group']);
        })
        ->filter(function ($lesson) use ($professor) {
            if (array_key_exists('professor', $_GET)) {
                return ($lesson['Teacher']['id'] == $professor);
            } elseif (!array_key_exists('group', $_GET)) {
                return ($lesson['Teacher']['name'] == "Исаков Сергей Сергеевич");
            } else {
                return true;
            }
        }))
        ->sortBy(['Number'])
        ->sortByDate('dayDate');
}
