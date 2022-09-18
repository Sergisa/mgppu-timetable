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

function getGroupsSignature($lesson): string
{
    $groupCodeList = collect($lesson['Group'])->pluck('name')->map(function ($code) {
        preg_match_all('/(\d{2}[А-Я]{2})-([А-Я]+)\((.+)\)([А-Я]+)-(\d)/u', $code, $m);
        return $m[2][0];
    });
    return ($groupCodeList->count() > 1) ? implode(', ', $groupCodeList->toArray()) : "";
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
