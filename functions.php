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

function convertUID($binary): string
{
    return "0x" . strtoupper(bin2hex($binary));
}

function getFileData($fileName): Collection
{
    ini_set('memory_limit', '-1');
    $pdo = new PDO('mysql:dbname=timetable;host=sergisa.ru', 'user15912_sergey', 'isakovs');
    $pdo->exec('SET CHARACTER SET UTF8');
    $response = $pdo->query('SELECT * FROM timetable')->fetchAll(PDO::FETCH_ASSOC);
    return collect($response);
}

function getGroupById($id)
{
    return getTimetable()->pluck("Group")->unique()->values()->where('id', '=', $id)->unique()->values()[0]['name'];
}

function getTeacherById($id)
{
    if ($id == "null") $id = null;
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

function getLessonType($typeID): object
{
    $types = [
        '0x80C9000C295831B711E8A95D5CD1DE1A' => ['full' => 'Семинар', 'shorthand' => 'Сем.'],
        '0xAD88005056B76B4C11E6B2C498121654' => ['full' => 'Лекции', 'shorthand' => 'Лек.'],
        '0xAD88005056B76B4C11E6B2C49FAB9534' => ['full' => 'Лабораторные работы', 'shorthand' => 'Лаб.'],
        '0xAD88005056B76B4C11E6B2C4A65DCFFA' => ['full' => 'Практические занятия', 'shorthand' => 'Пр.'],
        '0x878C000C292E7CB311E38348920B4846' => ['full' => 'Зачет', 'shorthand' => 'Зач.'],
        '0x80C7000C295831B711E83288B4B42A35' => ['full' => 'Консультация', 'shorthand' => 'Конс.'],
        '0x878C000C292E7CB311E38348920B4849' => ['full' => 'Экзамен', 'shorthand' => 'Экз.'],
        '0x878C000C292E7CB311E38348920B4848' => ['full' => 'Зачет с оценкой', 'shorthand' => 'З/О'],
    ];
    return (object)$types[$typeID];
}

function getLessonTypeSignature($typeID): ?string
{

    return getLessonType($typeID)->shorthand;
}

function getLessonFullType($typeID): ?string
{
    return getLessonType($typeID)->full;
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

function getGroupSpeciality($group): string
{
    return parseGroupCode($group)[3][0];
}

function getGroupYear($group): string
{
    return parseGroupCode($group)[1][0];
}

function getGroupFaculty($group): string
{
    return parseGroupCode($group)[2][0];
}

function getGroupSpecialization($group): string
{
    return parseGroupCode($group)[5][0];
}

function isSessionPart($lesson): string
{
    return !is_null($lesson["finalCheckType"]);
}

function getGroupsSignature($lesson): string
{
    $groupCodeList = collect($lesson['Group'])->pluck('name')->map(function ($group) {
        return getGroupFaculty($group) . " - " . getGroupSpeciality($group);
    });
    return "(" . implode(', ', $groupCodeList->toArray()) . ")";
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
                "id" => convertUID($item['BuildingID']),
                "name" => $item['Building']
            ],
            'floor' => [
                "id" => convertUID($item['FloorID']),
                "name" => $item['Floor']
            ],
            'room' => [
                "index" => $item['Room'],
                "id" => convertUID($item['RoomID'])
            ]
        ], "Coords")->prepend([
            'id' => convertUID($item['TeacherID']),
            'name' => $item['TeacherFIO'],
        ], "Teacher")->prepend([
            'id' => convertUID($item['GroupID']),
            'name' => $item['GroupCode'],
        ], "Group")->prepend([
            'id' => convertUID($item['DepartmentID']),
            'name' => $item['DepartmentName'],
            'code' => $item['DepartmentCode'],
        ], "Department")->map(function ($element, $key) {
            if ($key == 'TypeID') return convertUID($element);
            if ($key == 'DisciplineID') return convertUID($element);
            if ($key == 'SemesterID') return convertUID($element);
            if ($key == 'finalCheckTypeID') return convertUID($element);
            return $element;
        })->filter(function ($item, $key) {
            return !in_array($key, [
                "BuildingID",
                "FloorID",
                "RoomID",
                "GroupID",
                "TeacherID",
                "TeacherFIO",
                "DepartmentID",
                "DepartmentName",
                "DepartmentCode",
                "GroupCode"
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
    return joinParallelLessonsByGroup(getTimetable()
        ->filter(function ($lesson) {
            return str_contains($lesson['dayDate'], "." . getActiveMonth() . ".");
        })
        ->filter(function ($lesson) {
            return !array_key_exists('group', $_GET) || ($lesson['Group']['id'] == $_GET['group']);
        })
        ->filter(function ($lesson) {
            if (array_key_exists('professor', $_GET)) {
                return ($lesson['Teacher']['id'] == (($_GET['professor'] == "null") ? null : $_GET['professor']));
            } elseif (!array_key_exists('group', $_GET)) {
                return ($lesson['Teacher']['name'] == "Исаков Сергей Сергеевич");
            } else {
                return true;
            }
        }))
        ->sortBy(['Number'])
        ->sortByDate('dayDate');
}
