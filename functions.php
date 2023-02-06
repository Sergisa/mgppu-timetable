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
    if ($binary == null) return '';
    return "0x" . strtoupper(bin2hex($binary));
}

function getGroupById($id)
{
    return DataHandler::getInstance()->getGroups()->where('id', '=', $id)->values()[0]['name'];
}

function getTeacherById($id)
{
    if ($id == "null") $id = null;
    return DataHandler::getInstance()->getProfessors()->where('id', '=', $id)->values()[0]['name'];
}

function getBuildingById($id)
{
    if ($id == "null") $id = null;
    return DataHandler::getInstance()->getBuildings()->where('id', '=', $id)->values()[0]['name'];
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
        '0x80C7000C295831B711E825C13399551C' => ['full' => 'Контрольная работа', 'shorthand' => 'Кр.'],
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

function parseGroupCode($group): array
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

function getBlade(): BladeOne
{
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    return new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
}
