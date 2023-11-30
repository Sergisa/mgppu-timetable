<?php
declare(strict_types=1);
include_once __DIR__ . '/../functions.php';

use PHPUnit\Framework\TestCase;

final class ParseTextTest extends TestCase
{
    public function testGettingGroupYear(): void
    {
        $this->assertEquals(20, getGroupYear('20ИТ-ПИ(б/о)ПИП-1'), 'Неверное извлениче года группы');
        $this->assertEquals(21, getGroupYear('21ИТ-МО(б/о)ИСБД-1'), 'Неверное извлениче года группы');
        $this->assertEquals(19, getGroupYear('19ИТ-ПИ(б/о)ПИП-1'), 'Неверное извлениче года группы');
        $this->assertEquals(20, getGroupYear('20ПО-ППО(м/з)ППСО-2'), 'Неверное извлениче года группы');
        $this->assertEquals(21, getGroupYear('21ЭП-П(м/о)ППД-1'), 'Неверное извлениче года группы');

        $this->assertEquals(22, getGroupYear('22ЮП-П(б/о)П-1'), 'Неверное извлениче года группы');
        $this->assertEquals(22, getGroupYear('22ИЭП-П(б/о)ЭкП-1'), 'Неверное извлениче года группы');
        $this->assertEquals(22, getGroupYear('22ЮП-ППД(с/о)Д-1'), 'Неверное извлениче года группы');
    }

    public function testGettingGroupFaculty(): void
    {
        $this->assertEquals('ИТ', getGroupFaculty('20ИТ-ПИ(б/о)ПИП-1'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ИТ', getGroupFaculty('21ИТ-МО(б/о)ИСБД-1'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ИТ', getGroupFaculty('19ИТ-ПИ(б/о)ПИП-1'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ПО', getGroupFaculty('20ПО-ППО(м/з)ППСО-2'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ЭП', getGroupFaculty('21ЭП-П(м/о)ППД-1'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ЮП', getGroupFaculty('22ЮП-П(б/о)П-1'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ИЭП', getGroupFaculty('22ИЭП-П(б/о)ЭкП-1'), 'Неверное извеленичене факультета из кода группы');
        $this->assertEquals('ЮП', getGroupFaculty('22ЮП-ППД(с/о)Д-1'), 'Неверное извеленичене факультета из кода группы');
    }

    public function testGettingGroupSpecialization(): void
    {
        $this->assertEquals('ПИП', getGroupSpecialization('20ИТ-ПИ(б/о)ПИП-1'), "Неверное определение специализации группы");
        $this->assertEquals('ИСБД', getGroupSpecialization('21ИТ-МО(б/о)ИСБД-1'), "Неверное определение специализации группы");
        $this->assertEquals('ПИП', getGroupSpecialization('19ИТ-ПИ(б/о)ПИП-1'), "Неверное определение специализации группы");
        $this->assertEquals('ППСО', getGroupSpecialization('20ПО-ППО(м/з)ППСО-2'), "Неверное определение специализации группы");
        $this->assertEquals('ППД', getGroupSpecialization('21ЭП-П(м/о)ППД-1'), "Неверное определение специализации группы");
        $this->assertEquals('П', getGroupSpecialization('22ЮП-П(б/о)П-1'), "Неверное определение специализации группы");
        $this->assertEquals('ЭкП', getGroupSpecialization('22ИЭП-П(б/о)ЭкП-1'), "Неверное определение специализации группы");
        $this->assertEquals('Д', getGroupSpecialization('22ЮП-ППД(с/о)Д-1'), "Неверное определение специализации группы");
    }

    public function testGettingGroupCode(): void
    {
        $this->assertEquals('ПИ', getGroupSpeciality('20ИТ-ПИ(б/о)ПИП-1'), "Неверное определение специальности группы");
        $this->assertEquals('МО', getGroupSpeciality('21ИТ-МО(б/о)ИСБД-1'), "Неверное определение специальности группы");
        $this->assertEquals('ПИ', getGroupSpeciality('19ИТ-ПИ(б/о)ПИП-1'), "Неверное определение специальности группы");
        $this->assertEquals('ППО', getGroupSpeciality('20ПО-ППО(м/з)ППСО-2'), "Неверное определение специальности группы");
        $this->assertEquals('П', getGroupSpeciality('21ЭП-П(м/о)ППД-1'), "Неверное определение специальности группы");
        $this->assertEquals('П', getGroupSpeciality('22ЮП-П(б/о)П-1'), "Неверное определение специальности группы");
        $this->assertEquals('П', getGroupSpeciality('22ИЭП-П(б/о)ЭкП-1'), "Неверное определение специальности группы");
        $this->assertEquals('ППД', getGroupSpeciality('22ЮП-ППД(с/о)Д-1'), "Неверное определение специальности группы");
    }

    public function testGettingCourseNumber(): void
    {
        /**
         * $currentMonth = 03;
         * $currentYear = 23;
         */
        $this->assertEquals('3 курс', getCourseNumber('20ИТ-ПИ(б/о)ПИП-1'), "Неверное определение курса группы");
        $this->assertEquals('1 курс', getCourseNumber('22ИТ-ПИ(б/о)ПИП-1'), "Неверное определение курса группы");
        $this->assertEquals('2 курс', getCourseNumber('21ИТ-ПИ(б/о)ПИП-1'), "Неверное определение курса группы");
        $this->assertEquals('4 курс', getCourseNumber('19ИТ-ПИ(б/о)ПИП-1'), "Неверное определение курса группы");
        $this->assertEquals('3 курс', getCourseNumber('20ПО-ППО(м/з)ППСО-2'), "Неверное определение курса группы");
        $this->assertEquals('3 курс', getCourseNumber('20ИТ-ПИ(б/о)ПИП-1', 03, 23), "Неверное определение курса группы");
        $this->assertEquals('1 курс', getCourseNumber('22ИТ-ПИ(б/о)ПИП-1', 03, 23), "Неверное определение курса группы");
        $this->assertEquals('2 курс', getCourseNumber('21ИТ-ПИ(б/о)ПИП-1', 03, 23), "Неверное определение курса группы");
        $this->assertEquals('4 курс', getCourseNumber('19ИТ-ПИ(б/о)ПИП-1', 03, 23), "Неверное определение курса группы");
        $this->assertEquals('3 курс', getCourseNumber('20ПО-ППО(м/з)ППСО-2', 03, 23), "Неверное определение курса группы");
        $this->assertEquals('6 курс', getCourseNumber('17ЮП-КП(с/о)Э-1'), "Неверное определение курса группы");
        $this->assertEquals('1 курс', getCourseNumber('23ИТ-ПИ(б/о)ПИП-1'), "Неверное определение курса группы");
    }
}
