<?php
declare(strict_types=1);
include '../functions.php';

use PHPUnit\Framework\TestCase;

final class FunctionTesting extends TestCase
{
    public function testGettingGroupYear(): void
    {
        $this->assertEquals(20, getGroupYear('20ИТ-ПИ(б/о)ПИП-1'));
        $this->assertEquals(21, getGroupYear('21ИТ-МО(б/о)ИСБД-1'));
        $this->assertEquals(19, getGroupYear('19ИТ-ПИ(б/о)ПИП-1'));
        $this->assertEquals(20, getGroupYear('20ПО-ППО(м/з)ППСО-2'));
        $this->assertEquals(21, getGroupYear('21ЭП-П(м/о)ППД-1'));
    }

    public function testGettingCourseNumber(): void
    {
        /**
         * $currentMonth = 03;
         * $currentYear = 23;
         */
        $this->assertEquals('3 курс', getCourseNumber('20ИТ-ПИ(б/о)ПИП-1'), '20ИТ');
        $this->assertEquals('1 курс', getCourseNumber('22ИТ-ПИ(б/о)ПИП-1'), '22ИТ');
        $this->assertEquals('2 курс', getCourseNumber('21ИТ-ПИ(б/о)ПИП-1'), '21ИТ');
        $this->assertEquals('4 курс', getCourseNumber('19ИТ-ПИ(б/о)ПИП-1'), '19ИТ');
        $this->assertEquals('3 курс', getCourseNumber('20ПО-ППО(м/з)ППСО-2'), '20ИТ');

        $this->assertEquals('3 курс', getCourseNumber('20ИТ-ПИ(б/о)ПИП-1', 03, 23));
        $this->assertEquals('1 курс', getCourseNumber('22ИТ-ПИ(б/о)ПИП-1', 03, 23));
        $this->assertEquals('2 курс', getCourseNumber('21ИТ-ПИ(б/о)ПИП-1', 03, 23));
        $this->assertEquals('4 курс', getCourseNumber('19ИТ-ПИ(б/о)ПИП-1', 03, 23));
        $this->assertEquals('3 курс', getCourseNumber('20ПО-ППО(м/з)ППСО-2', 03, 23));
    }
}
