<?php

use Illuminate\Support\Collection;

function groupCollapsing($timetable): Collection
{
    return $timetable->map(function ($item) use ($timetable) {
        $newObj = collect($item)->prepend([
            'building' => [
                "id"=>$item['BuildingID'],
                "name"=>$item['Building']
            ],
            'floor' => [
                "id"=>$item['FloorID'],
                "name"=>$item['Floor']
            ],
            'room' => [
                "index"=>$item['Room'],
                "id"=>$item['RoomID']
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
