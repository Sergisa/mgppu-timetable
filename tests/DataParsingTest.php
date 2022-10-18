<?php
declare(strict_types=1);
include_once __DIR__ . '/../functions.php';

use PHPUnit\Framework\TestCase;

final class DataParsingTest extends TestCase
{
    function json_validator($data): bool
    {
        if (!empty($data)) {
            return is_string($data) && is_array(json_decode($data, true));
        }
        return false;
    }

    public function testJsonFileSyntax(): void
    {
        $fileName = __DIR__ . "/../data/tmtFull.json";
        ini_set('memory_limit', '-1');
        $myfile = fopen($fileName, "r") or die("Unable to open file!");
        $file = fread($myfile, filesize($fileName));
        fclose($myfile);
        $this->assertTrue($this->json_validator($file), "JSON file is not valid: " . json_last_error_msg());
        $this->assertFileExists($fileName, "Expecting file with timetableData does not exists");
    }

    public function hierarchicalCollapsing(): void
    {
        $lessons = [
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-ПИ(б/о)ПИП-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A24B29E07",
                "DisciplineID" => "0x80C3000C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ],
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-МО(б/о)ИСБД-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A3D22FF75",
                "DisciplineID" => "0x80C3000C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ]
        ];
        $collapsed = collapseDataHierarchically(collect($lessons));
        $this->assertArrayHasKey('Coords', $collapsed[0], "There is no 'Coords' key in lesson");
        $this->assertArrayHasKey('room', $collapsed[0]['Coords'], "There is no 'room' key in Coords");
        $this->assertArrayHasKey('id', $collapsed[0]['Coords']['room'], "There is no 'id' key in {Coords->room}");
        $this->assertArrayHasKey('index', $collapsed[0]['Coords']['room'], "There is no 'index' key in {Coords->room}");
        $this->assertArrayHasKey('floor', $collapsed[0]['Coords'], "There is no 'floor' key in Coords");
        $this->assertArrayHasKey('id', $collapsed[0]['Coords']['floor'], "There is no 'id' key in {Coords->floor}");
        $this->assertArrayHasKey('name', $collapsed[0]['Coords']['floor'], "There is no 'name' key in {Coords->floor}");
        $this->assertArrayHasKey('building', $collapsed[0]['Coords'], "There is no 'building' key in Coords");
        $this->assertArrayHasKey('id', $collapsed[0]['Coords']['building'], "There is no 'id' key in {Coords->building}");
        $this->assertArrayHasKey('name', $collapsed[0]['Coords']['building'], "There is no 'name' key in {Coords->building}");

        $this->assertArrayHasKey('Teacher', $collapsed[0], "There is no Teacher key in lesson");
        $this->assertArrayHasKey('id', $collapsed[0]['Teacher'], "There is no 'id' key in Teacher");
        $this->assertArrayHasKey('name', $collapsed[0]['Teacher'], "There is no 'name' key in Teacher");

        $this->assertArrayHasKey('Group', $collapsed[0], "No 'Group' key in lesson");
        $this->assertArrayHasKey('id', $collapsed[0]['Group'], "The group has no 'id' inside");
        $this->assertArrayHasKey('name', $collapsed[0]['Group'], "The group has no 'name' inside");

        $this->assertArrayHasKey('Department', $collapsed[0], "No 'Department' key in lesson");
        $this->assertArrayHasKey('id', $collapsed[0]['Department'], "The department has no 'id' inside");
        $this->assertArrayHasKey('name', $collapsed[0]['Department'], "The department has no 'name' inside");
        $this->assertArrayHasKey('code', $collapsed[0]['Department'], "The department has no 'name' inside");


        $this->assertArrayNotHasKey('BuildingID', $collapsed[0], "The BuildingID is left");
        $this->assertArrayNotHasKey('FloorID', $collapsed[0], "The FloorID is left");
        $this->assertArrayNotHasKey('RoomID', $collapsed[0], "The RoomID is left");
        $this->assertArrayNotHasKey('GroupID', $collapsed[0], "The GroupID is left");
        $this->assertArrayNotHasKey('TeacherID', $collapsed[0], "The TeacherID is left");
        $this->assertArrayNotHasKey('TeacherFIO', $collapsed[0], "The TeacherFIO is left");
        $this->assertArrayNotHasKey('DepartmentID', $collapsed[0], "The TeacherID is left");
        $this->assertArrayNotHasKey('DepartmentName', $collapsed[0], "The DepartmentName is left");
        $this->assertArrayNotHasKey('DepartmentCode', $collapsed[0], "The DepartmentCode is left");
        $this->assertArrayNotHasKey('GroupCode', $collapsed[0], "The GroupCode is left");
    }

    public function testMergingLessons(): void
    {
        $lessons = [
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-ПИ(б/о)ПИП-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A24B29E07",
                "DisciplineID" => "0x80C3000C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ],
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-МО(б/о)ИСБД-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A3D22FF75",
                "DisciplineID" => "0x80C3000C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ]
        ];
        $lessonsNotToMerge = [
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-ПИ(б/о)ПИП-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A24B29E07",
                "DisciplineID" => "0x80C3000C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ],
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-ПИ(б/о)ПИП-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "15.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A24B29E07",
                "DisciplineID" => "0x80C3020C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ],
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-ПИ(б/о)ПИП-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A24B29E07",
                "DisciplineID" => "0x80C30778295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ],
            [
                "Discipline" => "Разработка и стандартизация программного обеспечения",
                "DepartmentName" => "Информационные технологии",
                "DepartmentCode" => "ИТ",
                "GroupCode" => "19ИТ-МО(б/о)ИСБД-1",
                "SemesterName" => "Осенний",
                "Floor" => "2 этаж",
                "Building" => "Открытое шоссе д. 24 корп. 27",
                "Number" => "2 пара",
                "Type" => "Лекции",
                "TeacherFIO" => "Исаков Сергей Сергеевич",
                "Room" => "211-212",
                "dayDate" => "14.09.2022",
                "dayOfWeekName" => "среда",
                "TypeShort" => "Лек.",
                "TimeStart" => "10:40:00",
                "TimeEnd" => "12:10:00",
                "active" => "0x01",
                "BuildingID" => "0x80C3000C295831B711E7204C66A75A78",
                "GroupID" => "0x80CF000C295831B711E9C03A3D22FF75",
                "DisciplineID" => "0x80C3890C295831B711E7710C45CAB42D",
                "TypeID" => "0xAD88005056B76B4C11E6B2C498121654",
                "TeacherID" => "0x80DD000C295831C111EB5727106D690C",
                "RoomID" => "0x80C7000C295831B711E830D98DDA8037",
                "FloorID" => "0x80C7000C295831B711E816E801335455",
                "DepartmentID" => "0x80C7000C295831B711E85F52CCC70668",
                "SemesterID" => "0x80C4000C299AE95511E6FFDE22A08A7E",
                "_Fld7256RRef" => "0x00000000000000000000000000000000",
                "finalCheckType" => null
            ]
        ];

        $merged = joinParallelLessonsByGroup(collapseDataHierarchically(collect($lessons)));
        $notMerged = joinParallelLessonsByGroup(collapseDataHierarchically(collect($lessonsNotToMerge)));
        $this->assertCount(1, $merged->toArray(), "Lessons where not joined");
        $this->assertCount(2, $notMerged->toArray(), "Lessons where joined");
        $this->assertCount(2, $merged[0]['Group'], "Lessons where not joined");
        //$this->assertEquals(4, $notMerged);
    }
}
