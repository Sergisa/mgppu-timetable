<?php
declare(strict_types=1);
include '../functions.php';

use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    /**
     * @before
     */
    public function clearGET(): void
    {
        $_GET = [];
    }

    function testActiveYear()
    {
        $this->assertEquals(2022, getActiveYear(), 'Активный год неверен');
        $_GET['year'] = 2023;
        $this->assertEquals(2023, getActiveYear(), 'Активный год неверен');
    }

    function testNextYear()
    {
        $this->assertEquals((int)date('Y') + 1, getNextYear(), 'Следующий год неверен');
        $_GET['year'] = '2025';
        $this->assertEquals(2026, getNextYear(), 'Следующий год неверен');
        $_GET['year'] = 2025;
        $this->assertEquals(2026, getNextYear(), 'Следующий год неверен');
    }

    function testPreviousYear()
    {
        $this->assertEquals((int)date('Y') - 1, getPreviousYear(), 'Предыдущий год неверен');
        $_GET['year'] = 2025;
        $this->assertEquals(2024, getPreviousYear(), 'Предыдущий год неверен');
        $_GET['year'] = '2025';
        $this->assertEquals(2024, getPreviousYear(), 'Предыдущий год неверен');
    }

    /*******************MONTH**************************/
    function testActiveMonth()
    {
        $this->assertEquals(10, getActiveMonth(), 'Активный месяц неверен');
        $_GET['month'] = 12;
        $this->assertEquals(12, getActiveMonth(), 'Активный месяц неверен');
        $_GET['month'] = 1;
        $this->assertSame('01', getActiveMonth(), 'Активный месяц неверен');
        $_GET['month'] = '1';
        $this->assertSame('01', getActiveMonth(), 'Активный месяц неверен');
        $_GET['month'] = '01';
        $this->assertSame('01', getActiveMonth(), 'Активный месяц неверен');
    }

    function testNextMonth()
    {
        $this->assertEquals(11, getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = 12;
        $this->assertSame('01', getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = 10;
        $this->assertEquals(11, getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = 5;
        $this->assertSame('06', getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = '5';
        $this->assertSame('06', getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = '05';
        $this->assertSame('06', getNextMonth(), 'Следующий месяц неверен');
    }

    function testPreviousMonth()
    {
        $this->assertEquals(date('m') - 1, getPreviousMonth(), 'Предыдущий месяц неверен');
        $_GET['month'] = 12;
        $this->assertEquals(11, getPreviousMonth(), 'Предыдущий месяц неверен');
        $_GET['month'] = 1;
        $this->assertEquals(12, getPreviousMonth(), 'Предыдущий месяц неверен');
        $_GET['month'] = 10;
        $this->assertSame('09', getPreviousMonth(), 'Предыдущий месяц неверен');
        $_GET['month'] = '5';
        $this->assertSame('04', getPreviousMonth(), 'Предыдущий месяц неверен');
        $_GET['month'] = '05';
        $this->assertSame('04', getPreviousMonth(), 'Предыдущий месяц неверен');
    }

    /***********************LINK**************************/
    function testPrevLink()
    {
        $_GET['month'] = 8;
        $this->assertEquals('month=07', getPreviousMonthLink(), 'Ссылка предыдущего месяца не верна');
        $_GET['month'] = 1;
        $this->assertEquals('month=12&year=' . date('Y') - 1, getPreviousMonthLink(), 'Ссылка предыдущего месяца не верна');
        $_GET['month'] = '01';
        $this->assertEquals('month=12&year=' . date('Y') - 1, getPreviousMonthLink(), 'Ссылка предыдущего месяца не верна');

        $_GET['month'] = '01';
        $_GET['year'] = '2025';
        $this->assertEquals('month=12&year=2024', getPreviousMonthLink(), 'Ссылка предыдущего месяца не верна');
    }

    function testNextLink()
    {
        $_GET['month'] = 9;
        $this->assertEquals('month=10', getNextMonthLink(), 'Ссылка следующего месяца неверна');
        $_GET['month'] = '09';
        $this->assertEquals('month=10', getNextMonthLink(), 'Ссылка следующего месяца неверна');
        $_GET['month'] = 12;
        $this->assertEquals('month=01&year=' . (int)date('Y') + 1, getNextMonthLink(), 'Ссылка следующего месяца неверна');
        $_GET['month'] = 12;
        $_GET['year'] = 2024;
        $this->assertEquals('month=01&year=2025', getNextMonthLink(), 'Ссылка следующего месяца неверна');
    }

    function testConvertNum()
    {
        $this->assertSame('05', convertNum(5), 'Преобразование цифры неверно');
        $this->assertSame(12, convertNum(12), 'Преобразование цифры неверно');
        $this->assertSame(10, convertNum(10), 'Преобразование цифры неверно');
        $this->assertSame('09', convertNum(9), 'Преобразование цифры неверно');

    }
}
