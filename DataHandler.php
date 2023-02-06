<?php

use Illuminate\Support\Collection;

include 'functions.php';

class DataHandler
{
    private static ?DataHandler $_instance = null;
    private Collection $timetable;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    static public function getInstance(): ?DataHandler
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    private function getPDO(): PDO
    {
        $pdo = new PDO('mysql:dbname=timetable;host=sergisa.ru', 'user15912_sergey', 'isakovs');
        $pdo->exec('SET CHARACTER SET UTF8');
        return $pdo;
    }

    function getDatabaseData($forMonth = false): DataHandler
    {
        $monthYear = "." . getActiveMonth() . "." . getActiveYear();
        if ($forMonth) {
            $this->timetable = collect($this->getPDO()->query("SELECT * FROM timetable WHERE dayDate LIKE '%$monthYear%'")->fetchAll(PDO::FETCH_ASSOC));
        } else {
            $this->timetable = collect($this->getPDO()->query("SELECT * FROM timetable")->fetchAll(PDO::FETCH_ASSOC));
        }
        return $this;
    }

    function getGroups(): Collection
    {
        return collect($this->getPDO()
            ->query("SELECT DISTINCT GroupCode AS name, CONCAT('0x', HEX(GroupID)) AS id FROM timetable ORDER BY GroupCode")
            ->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    function getProfessors(): Collection
    {
        return collect($this->getPDO()
            ->query("SELECT DISTINCT TeacherFIO AS name, CONCAT('0x', HEX(TeacherID)) AS id FROM timetable ORDER BY TeacherFIO")
            ->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    function getBuildings(): Collection
    {
        return collect($this->getPDO()
            ->query("SELECT DISTINCT Building AS name, CONCAT('0x', HEX(BuildingID)) AS id FROM timetable ORDER BY Building")
            ->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    /**
     * @param Collection $timetable
     * @return DataHandler Возвращает расписание с объединёнными парами
     */
    function joinParallelLessonsByGroup(Collection $timetable): DataHandler
    {
        $this->timetable= $timetable->map(function ($lesson) use ($timetable) {
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
        return $this;
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
     * @return DataHandler Возвращает иерархическую свертку расписания
     */
    function collapseDataHierarchically(): DataHandler
    {
        $tmt = $this->timetable;
        $this->timetable = $this->timetable->map(function ($item) {
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
        return $this;
    }

    /**
     * @param bool $forMonth Вернуть данные на конкретный месяц
     * @return Collection Возвращает расписание полное или на месяц, с иерархической свёрткой
     */
    function prepareTimetable(bool $forMonth = false): Collection
    {
        return $this->getDatabaseData()->collapseDataHierarchically()->getTimetable();
    }

    /**
     * @return Collection Возвращает блок расписания на месяц с объединёнными одновременными парами
     */
    function getPreparedTimetable(): Collection
    {
        return $this->joinParallelLessonsByGroup()$this->prepareTimetable(true)
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

    function getTimetable(): Collection
    {
        return $this->timetable;
    }
}

