-- Получение списка учебных годов
SELECT CONVERT(CHAR(32), УчебныеГода._IDRRef, 2) AS id,
       УчебныеГода._Description                  AS name,
       УчебныеГода._Code                         AS estimates,
       УчебныеГода._Fld2722,
       УчебныеГода._Fld2723,
       УчебныеГода._Fld2724,
       УчебныеГода._Fld2725
FROM _Reference2696 As УчебныеГода
WHERE УчебныеГода._IDRRef IN
      (SELECT DISTINCT РегистрДисциплины._Fld7246RRef FROM _InfoRg7081 AS РегистрДисциплины)
ORDER BY УчебныеГода._Description DESC

-- Получение списка семестров
SELECT CONVERT(CHAR(32), Семестры._IDRRef, 2) AS id,
       Семестры._Description                  AS name
FROM _Reference5069 AS Семестры
ORDER BY Семестры._Code


--Список преподавателей
SELECT _Fld7885, _Fld589
FROM _InfoRg7084 AS ПреподавателиДисциплин
         LEFT JOIN _Reference2202 AS Преподаватели
                   ON ПреподавателиДисциплин._Fld7243RRef = Преподаватели._IDRRef
         LEFT JOIN _Reference2527 AS ДолжностиППС ON Преподаватели._Fld2528RRef = ДолжностиППС._IDRRef
         LEFT JOIN _InfoRg378 AS ФИОФизЛиц ON Преподаватели._OwnerIDRRef = ФИОФизЛиц._Fld379RRef

--Список кабинетов
SELECT Помещения._Description   AS Room,
       ЭтажиЗданий._Description AS Floor,
       Здания._Description      AS Building,
       Помещения._IDRRef        AS RoomId,
       ЭтажиЗданий._IDRRef      AS FloorID,
       Здания._IDRRef           AS BuildingID
FROM _Reference2863 AS Помещения
         LEFT JOIN _Reference2865 AS ЭтажиЗданий ON Помещения._OwnerIDRRef = ЭтажиЗданий._IDRRef
         LEFT JOIN _Reference2864 AS Здания
                   ON ЭтажиЗданий._OwnerIDRRef = Здания._IDRRef


-- Получение списка задействованных учебных подразделений (институты и факультеты) по семестру и учебному году
SELECT CONVERT(CHAR(32), Институты._IDRRef, 2) AS id,
       Институты._Description                  AS name,
       Институты._Fld152                       as abbr
FROM _Reference151 AS Институты
WHERE Институты._IDRRef in
      (SELECT DISTINCT Институты._IDRRef
       FROM _InfoRg7081 AS РегистрДисциплины
                INNER JOIN _Reference176 AS УчебныеГруппы ON РегистрДисциплины._Fld7251RRef = УчебныеГруппы._IDRRef
                INNER JOIN _Reference151 AS Институты ON УчебныеГруппы._Fld1234RRef = Институты._IDRRef
                LEFT JOIN _Reference5069 AS Семестры ON РегистрДисциплины._Fld7247RRef = Семестры._IDRRef
       WHERE РегистрДисциплины._Fld7246RRef = :academicYearId /* Учебный год Принимать внешним параметром*/
         AND Семестры._IDRRef = :semesterId1
          /* Осенний (0x80C4000C299AE95511E6FFDE22A08A7E), Весенний(0x80C4000C299AE95511E6FFDE22A08A7D)*/
      )
ORDER BY Институты._Description

-- Группы по году, семестру и факультету
SELECT CONVERT(CHAR(32), УчебныеГруппы._IDRRef, 2) AS id,
       УчебныеГруппы._Code                         AS name
FROM _Reference176 AS УчебныеГруппы
WHERE УчебныеГруппы._IDRRef IN
      (SELECT DISTINCT РегистрДисциплины._Fld7251RRef
       FROM _InfoRg7081 AS РегистрДисциплины
                INNER JOIN _Reference176 AS УчебныеГруппы ON РегистрДисциплины._Fld7251RRef = УчебныеГруппы._IDRRef
                INNER JOIN _Reference151 AS Институты ON УчебныеГруппы._Fld1234RRef = Институты._IDRRef
                LEFT JOIN _Reference5069 AS Семестры ON РегистрДисциплины._Fld7247RRef = Семестры._IDRRef
       WHERE Институты._IDRRef = 0x80C7000C295831B711E85F52CCC70668 -- Институт Принимать внешним параметром
          --AND РегистрДисциплины._Fld7246RRef = 0x80DF000C295831C111EBC7A8E96D12AE -- 80DF000C295831C111EBC7A8E96D12AE,2021/2022 80DB000C295831C111EAC5C36D634262,2020/2021
          --AND Семестры._IDRRef = 0x80C4000C299AE95511E6FFDE22A08A7D /* Осенний (0x80C4000C299AE95511E6FFDE22A08A7E), Весенний(0x80C4000C299AE95511E6FFDE22A08A7D)*/
      )
ORDER BY name

SELECT Дисциплины._Description                                                                             AS Discipline,
       Институты._Description                                                                              AS DepartmentName,
       Институты._Fld152                                                                                   AS DepartmentCode,
       УчебныеГруппы._Code                                                                                 AS GroupCode,
       Семестры._Description                                                                               AS SemesterName,
       ЭтажиЗданий._Description                                                                            AS Floor,
       Здания._Description                                                                                 AS Building,
       УчебныеПары._Description                                                                            AS Number,
       Преподаватели._Description                                                                          AS TeacherFIO,
       Помещения._Description                                                                              AS Room,
       CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104)                                            AS dayDate,
       DATENAME(weekday, ДниПроведенияЗанятий._Fld7241)                                                    AS dayOfWeekName,
       ВидыЗанятий._Fld7440                                                                                AS TypeShort,
       IIF(ИтоговыйКонтроль._Description IS NULL, ВидыЗанятий._IDRRef, ИтоговыйКонтроль._IDRRef)           AS TypeID,
       IIF(ИтоговыйКонтроль._Description IS NULL, ВидыЗанятий._Description, ИтоговыйКонтроль._Description) AS Type,
       IIF(ИтоговыйКонтроль._Description IS NOT NULL, CONVERT(VARCHAR, РегистрДисциплины._Fld7258, 108),
           CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld7102, 108))                                            AS TimeStart,
       IIF(ИтоговыйКонтроль._Description IS NOT NULL, CONVERT(VARCHAR, РегистрДисциплины._Fld7259, 108),
           CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld7103, 108))                                            AS TimeEnd,
       SUBSTRING(CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld7102, 108), 0, 6) + '-' +
       SUBSTRING(CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld7103, 108), 0, 6)                                AS TimeRange,
       РегистрДисциплины._Active                                                                           AS active,
       Здания._IDRRef                                                                                      AS BuildingID,
       УчебныеГруппы._IDRRef                                                                               AS GroupID,
       Дисциплины._IDRRef                                                                                  AS DisciplineID,
       Преподаватели._IDRRef                                                                               as TeacherID,
       Помещения._IDRRef                                                                                   AS RoomID,
       ЭтажиЗданий._IDRRef                                                                                 AS FloorID,
       Институты._IDRRef                                                                                   AS DepartmentID,
       Семестры._IDRRef                                                                                    AS SemesterID,
       ИтоговыйКонтроль._Description                                                                       as finalCheckType,
       РегистрДисциплины._Fld7255RRef                                                                      as finalCheckTypeID,
       IIF(ИтоговыйКонтроль._Description IS NULL, 0, 1)                                                    as isSession

FROM _InfoRg7081 AS РегистрДисциплины
         LEFT JOIN _Reference4684 AS ВидыЗанятий
                   ON РегистрДисциплины._Fld7250RRef = ВидыЗанятий._IDRRef
         LEFT JOIN _Reference4514 AS УчебныеПары ON РегистрДисциплины._Fld7249RRef = УчебныеПары._IDRRef
         LEFT JOIN _Reference7092 AS ВидыНедели ON РегистрДисциплины._Fld7253RRef = ВидыНедели._IDRRef
         LEFT JOIN _Reference1318 AS Дисциплины ON РегистрДисциплины._Fld7252RRef = Дисциплины._IDRRef
         LEFT JOIN _Reference5069 AS Семестры ON РегистрДисциплины._Fld7247RRef = Семестры._IDRRef
         LEFT JOIN _Reference2658 AS ИтоговыйКонтроль ON РегистрДисциплины._Fld7255RRef = ИтоговыйКонтроль._IDRRef
         LEFT JOIN _Reference4729 AS Подгруппы ON РегистрДисциплины._Fld7245RRef = Подгруппы._IDRRef
         LEFT JOIN _Reference5126 AS НомераПодгрупп ON Подгруппы._Fld5128RRef = НомераПодгрупп._IDRRef
         LEFT JOIN _Reference2863 AS Помещения ON РегистрДисциплины._Fld7254RRef = Помещения._IDRRef
         LEFT JOIN _Reference2865 AS ЭтажиЗданий ON Помещения._OwnerIDRRef = ЭтажиЗданий._IDRRef
         LEFT JOIN _Reference2864 AS Здания ON ЭтажиЗданий._OwnerIDRRef = Здания._IDRRef
         LEFT JOIN _Reference176 AS УчебныеГруппы ON РегистрДисциплины._Fld7251RRef = УчебныеГруппы._IDRRef
         LEFT JOIN _Reference151 AS Институты ON УчебныеГруппы._Fld1234RRef = Институты._IDRRef
         LEFT JOIN _Reference7082_VT7083 AS ТчРасписаниеЗвонков
                   ON РегистрДисциплины._Fld7244RRef = ТчРасписаниеЗвонков._Reference7082_IDRRef AND
                      РегистрДисциплины._Fld7249RRef = ТчРасписаниеЗвонков._Fld7101RRef
         LEFT JOIN _InfoRg7084 AS ПреподавателиДисциплин ON РегистрДисциплины._Fld7257 = ПреподавателиДисциплин._Fld7242
         LEFT JOIN _Reference2202 AS Преподаватели ON ПреподавателиДисциплин._Fld7243RRef = Преподаватели._IDRRef
         LEFT JOIN _InfoRg7239 AS ДниПроведенияЗанятий ON РегистрДисциплины._Fld7257 = ДниПроведенияЗанятий._Fld7240
         LEFT JOIN _Document7087 AS ДокРасписание ON РегистрДисциплины._RecorderRRef = ДокРасписание._IDRRef


WHERE РегистрДисциплины._Fld7246RRef = 0x80EC000C295831C111ECF777467EF0FF
    /*0x80EC000C295831C111ECF777467EF0FF 2022/2023*/
  --AND РегистрДисциплины._Fld7251RRef = :studentGroupId /* Учебная группа*/
  --AND CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104) = '29.03.2022'
  AND Семестры._IDRRef = 0x80C4000C299AE95511E6FFDE22A08A7E
  AND CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104) LIKE '%.01.2023%'
  --AND Институты._Description = :departmentCode
  --AND Институты._Fld152 = :departmentCode
  --AND ВидыЗанятий._Fld7440 IS NULL
  --AND _Fld7250RRef NOT IN (SELECT _IDRRef FROM _Reference4684)
    /* Осенний (0x80C4000C299AE95511E6FFDE22A08A7E), Весенний(0x80C4000C299AE95511E6FFDE22A08A7D)*/

  --AND CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104) LIKE '%.01.2023%'
  --AND Преподаватели._Description = 'Исаков Сергей Сергеевич'
  --AND Дисциплины._Description LIKE '%Общая псих%'
  --AND ВидыЗанятий._IDRRef IS NULL
  --AND ИтоговыйКонтроль._Description IS NOT NULL
ORDER BY ДниПроведенияЗанятий._Fld7241


SELECT *
from _InfoRg7081
WHERE _Fld7250RRef not in (select _IDRRef from _Reference4684)

select *
from _Document7087

select *
from _Reference2658 AS ФормыИтоговогоКонтроля
SELECT *
FROM _Reference4684 AS ВидыЗанятий
