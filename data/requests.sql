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
       CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld5104, 104)                                            AS dayDate,
       DATENAME(weekday, ДниПроведенияЗанятий._Fld5104)                                                    AS dayOfWeekName,
       ВидыЗанятий._Fld5205                                                                                AS TypeShort,
       ВидыЗанятий._Description                                                                            AS TypeFull,
       IIF(ИтоговыйКонтроль._Description IS NULL, ВидыЗанятий._IDRRef, ИтоговыйКонтроль._IDRRef)           AS TypeID,
       IIF(ИтоговыйКонтроль._Description IS NULL, ВидыЗанятий._Description, ИтоговыйКонтроль._Description) AS Type,
       IIF(ИтоговыйКонтроль._Description IS NOT NULL, CONVERT(VARCHAR, РегистрДисциплины._Fld5212, 108),
           CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld4521, 108))                                            AS TimeStart,
       IIF(ИтоговыйКонтроль._Description IS NOT NULL, CONVERT(VARCHAR, РегистрДисциплины._Fld5213, 108),
           CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld4522, 108))                                            AS TimeEnd,
       SUBSTRING(CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld4521, 108), 0, 6) + '-' +
       SUBSTRING(CONVERT(VARCHAR, ТчРасписаниеЗвонков._Fld4522, 108), 0, 6)                                AS TimeRange,
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
       РегистрДисциплины._Fld5211RRef                                                                      as finalCheckTypeID,
       IIF(ИтоговыйКонтроль._Description IS NULL, 0, 1)                                                    as isSession


FROM _InfoRg5108 AS РегистрДисциплины
         LEFT JOIN _Reference4684 AS ВидыЗанятий
                   ON РегистрДисциплины._Fld5115RRef = ВидыЗанятий._IDRRef
         LEFT JOIN _Reference4514 AS УчебныеПары ON РегистрДисциплины._Fld5114RRef = УчебныеПары._IDRRef
         LEFT JOIN _Reference5170 AS ВидыНедели ON РегистрДисциплины._Fld5173RRef = ВидыНедели._IDRRef
         LEFT JOIN _Reference1318 AS Дисциплины ON РегистрДисциплины._Fld5169RRef = Дисциплины._IDRRef
         LEFT JOIN _Reference5069 AS Семестры ON РегистрДисциплины._Fld5112RRef = Семестры._IDRRef
         LEFT JOIN _Reference2658 AS ИтоговыйКонтроль ON РегистрДисциплины._Fld5211RRef = ИтоговыйКонтроль._IDRRef
         LEFT JOIN _Reference4729 AS Подгруппы ON РегистрДисциплины._Fld5110RRef = Подгруппы._IDRRef
         LEFT JOIN _Reference5126 AS НомераПодгрупп ON Подгруппы._Fld5128RRef = НомераПодгрупп._IDRRef
         LEFT JOIN _Reference2863 AS Помещения ON РегистрДисциплины._Fld5175RRef = Помещения._IDRRef
         LEFT JOIN _Reference2865 AS ЭтажиЗданий ON Помещения._OwnerIDRRef = ЭтажиЗданий._IDRRef
         LEFT JOIN _Reference2864 AS Здания ON ЭтажиЗданий._OwnerIDRRef = Здания._IDRRef
         LEFT JOIN _Reference176 AS УчебныеГруппы ON РегистрДисциплины._Fld5140RRef = УчебныеГруппы._IDRRef
         LEFT JOIN _Reference151 AS Институты ON УчебныеГруппы._Fld1234RRef = Институты._IDRRef
         LEFT JOIN _Reference4515_VT4518 AS ТчРасписаниеЗвонков
                   ON РегистрДисциплины._Fld5109RRef = ТчРасписаниеЗвонков._Reference4515_IDRRef AND
                      РегистрДисциплины._Fld5114RRef = ТчРасписаниеЗвонков._Fld4520RRef
         LEFT JOIN _InfoRg5105 AS ПреподавателиДисциплин ON РегистрДисциплины._Fld9852 = ПреподавателиДисциплин._Fld5106
         LEFT JOIN _Reference2202 AS Преподаватели ON ПреподавателиДисциплин._Fld5107RRef = Преподаватели._IDRRef
         LEFT JOIN _InfoRg5102 AS ДниПроведенияЗанятий ON РегистрДисциплины._Fld9852 = ДниПроведенияЗанятий._Fld5103
         LEFT JOIN _Document5071 AS ДокРасписание ON РегистрДисциплины._RecorderRRef = ДокРасписание._IDRRef


WHERE РегистрДисциплины._Fld5111RRef = 0x80EC000C295831C111ECF777467EF0FF
    /*0x80EC000C295831C111ECF777467EF0FF 2022/2023*/
      --AND РегистрДисциплины._Fld7251RRef = :studentGroupId /* Учебная группа*/
      --AND CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104) = '29.03.2022'

      --AND Семестры._IDRRef = 0x80C4000C299AE95511E6FFDE22A08A7D
      --AND CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104) LIKE '%.01.2023%'
      --AND Институты._Description = :departmentName
      --AND Институты._Fld152 = :departmentCode
      --AND ВидыЗанятий._Fld7440 IS NULL
      --AND _Fld7250RRef NOT IN (SELECT _IDRRef FROM _Reference4684)
    /* Осенний (0x80C4000C299AE95511E6FFDE22A08A7E), Весенний(0x80C4000C299AE95511E6FFDE22A08A7D)*/

      --AND CONVERT(VARCHAR(10), ДниПроведенияЗанятий._Fld7241, 104) LIKE '%.01.2023%'
      --AND Преподаватели._Description = 'Исаков Сергей Сергеевич'
      --AND Дисциплины._Description LIKE '%Общая псих%'
      --AND ВидыЗанятий._IDRRef IS NULL
      --AND ИтоговыйКонтроль._Description IS NOT NULL
ORDER BY ДниПроведенияЗанятий._Fld5104;