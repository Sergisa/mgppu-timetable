<?php
include 'date.php';

use eftec\bladeone\BladeOne;
use Illuminate\Support\Collection;

Collection::macro('sortByDate', function (string $column = 'created_at', bool $descending = false) {
    return $this->sortBy(function ($datum) use ($column) {
        return strtotime(((object)$datum)->$column);
    }, SORT_REGULAR, $descending);
});
function getDays(): array
{
    return [
        1 => "Понедельник",
        2 => "Вторник",
        3 => "Среда",
        4 => "Четверг",
        5 => "Пятница",
        6 => "Суббота",
        7 => "Воскресенье",
    ];
}

function getMonths(): array
{
    return [
        1  => "Январь",
        2  => "Февраль",
        3  => "Март",
        4  => "Апрель",
        5  => "Май",
        6  => "Июнь",
        7  => "Июль",
        8  => "Август",
        9  => "Сентябрь",
        10 => "Октябрь",
        11 => "Ноябрь",
        12 => "Декабрь"
    ];
}

function getPDO(): PDO
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $pdo = new PDO("mysql:dbname=timetable;host={$_ENV['DB_HOST']}", "{$_ENV['DB_USER']}", "{$_ENV['DB_PASSWORD']}");
    $pdo->exec('SET CHARACTER SET UTF8');
    return $pdo;
}

function getDatabaseData($forMonth = false): Collection
{
    $monthYear = "." . getActiveMonth() . "." . getActiveYear();
    if ($forMonth) {
        return collect(getPDO()->query("SELECT * FROM timetable WHERE dayDate LIKE '%$monthYear%'")->fetchAll(PDO::FETCH_ASSOC));
    } else {
        return collect(getPDO()->query("SELECT * FROM timetable")->fetchAll(PDO::FETCH_ASSOC));
    }
}

function getGroups(): Collection
{
    return collect(getPDO()
        ->query("SELECT DISTINCT GroupCode AS name, CONCAT('0x', HEX(GroupID)) AS id FROM timetable ORDER BY GroupCode")
        ->fetchAll(PDO::FETCH_ASSOC)
    );
}

function getProfessors(): Collection
{
    return collect(getPDO()
        ->query("SELECT DISTINCT TeacherFIO AS name, CONCAT('0x', HEX(TeacherID)) AS id FROM timetable ORDER BY TeacherFIO")
        ->fetchAll(PDO::FETCH_ASSOC)
    );
}

function getBuildings(): Collection
{
    return collect(getPDO()
        ->query("SELECT DISTINCT Building AS name, CONCAT('0x', HEX(BuildingID)) AS id FROM timetable ORDER BY Building")
        ->fetchAll(PDO::FETCH_ASSOC)
    );
}

function convertUID($binary): string
{
    if ($binary == null) return '';
    return "0x" . strtoupper(bin2hex($binary));
}

function getGroupById($id)
{
    return getGroups()->where('id', '=', $id)->values()[0]['name'];
}

function getTeacherById($id)
{
    if ($id == "null") $id = null;
    return getProfessors()->where('id', '=', $id)->values()[0]['name'];
}

function getBuildingById($id)
{
    if ($id == "null") $id = null;
    return getBuildings()->where('id', '=', $id)->values()[0]['name'];
}

function getLessonSignature($lesson): string
{
    return $lesson['Discipline'] . ' ';
}

function getLessonType($typeID): object
{
    $types = [
        '0x80C9000C295831B711E8A95D5CD1DE1A' => ['full' => 'Семинар', 'shorthand' => 'Сем.'],
        '0xBB29005056AF9F6411EDDA12014C6F3E' => ['full' => 'Семинар', 'shorthand' => 'Сем.'],
        '0xAD88005056B76B4C11E6B2C498121654' => ['full' => 'Лекции', 'shorthand' => 'Лек.'],
        '0xAD88005056B76B4C11E6B2C49FAB9534' => ['full' => 'Лабораторные работы', 'shorthand' => 'Лаб.'],
        '0xAD88005056B76B4C11E6B2C4A65DCFFA' => ['full' => 'Практические занятия', 'shorthand' => 'Пр.'],
        '0x878C000C292E7CB311E38348920B4846' => ['full' => 'Зачет', 'shorthand' => 'Зач.'],
        '0x80C7000C295831B711E83288B4B42A35' => ['full' => 'Консультация', 'shorthand' => 'Конс.'],
        '0x878C000C292E7CB311E38348920B4849' => ['full' => 'Экзамен', 'shorthand' => 'Экз.'],
        '0x878C000C292E7CB311E38348920B4848' => ['full' => 'Зачет с оценкой', 'shorthand' => 'З/О'],
        '0xB81900155D03030011EDBE8FA93CA436' => ['full' => 'Контрольная работа', 'shorthand' => 'К/р.'],
        '0x80C7000C295831B711E825C13399551C' => ['full' => 'Контрольная работа', 'shorthand' => 'К/р.'],
        '0x80C6000C299AE95511E766EF66B8A65C' => ['full' => 'Самостоятельная работа', 'shorthand' => 'Ср.'],
        '0x80C6000C299AE95511E7916A5EC99EDA' => ['full' => 'Экзамен', 'shorthand' => 'Экз.'],
        '0x80DA000C299AE95F11E97E0862BE1F71' => ['full' => 'Консультация', 'shorthand' => 'Конс.'],
        '0x80DA000C299AE95F11E97E0862BE1F72' => ['full' => 'Зачет', 'shorthand' => 'Зач.'],
        '0x80DA000C299AE95F11E97E0876AC4C41' => ['full' => 'Зачет с оценкой', 'shorthand' => 'З/О'],
        '0x80F1000C299AE95F11EAE93351306121' => ['full' => 'Мастер-класс', 'shorthand' => 'М-к'],
        '0x80F3000C299AE95F11EB1934438F30F7' => ['full' => 'Курсовая работа', 'shorthand' => 'Ку/Р'],

        '0x80C7000C295831B711E83288B4B42A36' => ['full' => 'Защита практики'],
        '0x80C7000C295831B711E83288B4B42A37' => ['full' => 'Защита НИР'],
        '0x80C7000C295831B711E83288B4B42A38' => ['full' => 'ВКР'],
        '0x80C7000C295831B711E889974F855F04' => ['full' => 'Дифференцированный зачет'],
        '0x80CB000C295831B711E91A7972F818B1' => ['full' => 'Эссе'],
        '0x878C000C292E7CB311E38348920B4847' => ['full' => 'Защита курсового проекта'],
        '0x878C000C292E7CB311E38348920B484A' => ['full' => 'Защита курсовой работы'],
        '0xB81900155D03030011EDBE8FA93CA437' => ['full' => 'Интернет экзамен'],
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

function isTeacherDefined($get = null): bool
{
    if ($get == null) {
        $get = $_GET;
    }
    return array_key_exists('professor', $get);
}

function isGroupDefined($get = null): bool
{
    if ($get == null) {
        $get = $_GET;
    }
    return array_key_exists('group', $get);
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

/**
 * @param Collection $timetable
 * @return Collection Возвращает расписание с объединёнными парами
 */
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
        return $item['dayDate'] . $item['Number'] . $item['Coords']['room']['id'] . $item['DisciplineID'];
    });
}

/**
 * Иерархия следующая: <br>
 *      Coords
 *           ----building
 *               ----id
 *               ----name
 *           ----floor
 *               ----id
 *               ----name
 *           ----room
 *               ----id
 *               ----index
 *      Teacher
 *           ----id
 *           ----name
 *      Group
 *           ----id
 *           ----name
 *      Department
 *           ----id
 *           ----name
 *           ----code
 *
 * @param $timetable Collection Чистые данные расписания
 * @return Collection Возвращает иерархическую свертку расписания
 */
function collapseDataHierarchically(Collection $timetable): Collection
{
    return $timetable->map(function ($item) use ($timetable) {
        $newObj = collect($item)->prepend([
            'building' => [
                "id"   => convertUID($item['BuildingID']),
                "name" => $item['Building']
            ],
            'floor'    => [
                "id"   => convertUID($item['FloorID']),
                "name" => $item['Floor']
            ],
            'room'     => [
                "index" => $item['Room'],
                "id"    => convertUID($item['RoomID'])
            ]
        ], "Coords")->prepend([
            'id'   => convertUID($item['TeacherID']),
            'name' => $item['TeacherFIO'],
        ], "Teacher")->prepend([
            'id'   => convertUID($item['GroupID']),
            'name' => $item['GroupCode'],
        ], "Group")->prepend([
            'id'   => convertUID($item['DepartmentID']),
            'name' => $item['DepartmentName'],
            'code' => $item['DepartmentCode'],
        ], "Department")->map(function ($element, $key) {
            if ($key == 'TypeID') return convertUID($element);
            if ($key == 'DisciplineID') return convertUID($element);
            if ($key == 'SemesterID') return convertUID($element);
            if ($key == 'finalCheckTypeID') return convertUID($element);
            if ($key == 'active') return convertUID($element);
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

/**
 * @param bool $forMonth Вернуть данные на конкретный месяц
 * @return Collection Возвращает расписание полное или на месяц, с иерархической свёрткой
 */
function getTimetable(bool $forMonth = false): Collection
{
    $timetable = getDatabaseData($forMonth);
    return collapseDataHierarchically($timetable); //FIXME: Общая психология пропала для преподавателя
}

/**
 * @return Collection Возвращает блок расписания на месяц с объединёнными одновременными парами
 */
function getPreparedTimetable(): Collection
{
    return joinParallelLessonsByGroup(getTimetable(true)
        ->filter(function ($lesson) {
            return !array_key_exists('group', $_GET) || ($lesson['Group']['id'] == $_GET['group']);
        })->filter(function ($lesson) {
            return !array_key_exists('building', $_GET) || ($lesson['Coords']['building']['id'] == $_GET['building']);
        })->filter(function ($lesson) {
            if (array_key_exists('professor', $_GET)) {
                return ($lesson['Teacher']['id'] == (($_GET['professor'] == "null") ? null : $_GET['professor']));
            } else {
                return true;
            }
        }))->sortBy(['Number'])
        ->sortByDate('dayDate');
}

function getBlade(): BladeOne
{
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    return new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
}
