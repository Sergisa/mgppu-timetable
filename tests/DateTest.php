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
        $this->assertEquals(2023, getNextYear(), 'Следующий год неверен');
    }


    function testActiveMonth()
    {
        $this->assertEquals(10, getActiveMonth(), 'Активный месяц неверен');
        $_GET['month'] = 12;
        $this->assertEquals(12, getActiveMonth(), 'Активный месяц неверен');
    }

    function testNextMonth()
    {
        $this->assertEquals(11, getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = 12;
        $this->assertEquals('01', getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = 11;
        $this->assertEquals(12, getNextMonth(), 'Следующий месяц неверен');
        $_GET['month'] = 5;
        $this->assertEquals('06', getNextMonth(), 'Следующий месяц неверен');
    }
}
