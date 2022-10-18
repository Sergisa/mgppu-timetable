<?php
function convertNum($num)
{
    if ($num < 10) {
        return '0' . $num;
    } else {
        return $num;
    }
}

function getActiveMonth(): int|string
{
    return convertNum(array_key_exists('month', $_GET) ? (int)$_GET['month'] : (int)date('m'));
}

function getNextMonthLink(): string
{
    return http_build_query(array_merge(
        $_GET,
        ['month' => getNextMonth()],
        getActiveMonth() == 12 ? ['year' => getNextYear()] : []
    ));
}

function getPreviousMonthLink(): string
{
    return http_build_query(array_merge(
        $_GET,
        ['month' => getPreviousMonth()],
        getActiveMonth() == 1 ? ['year' => getPreviousYear()] : []
    ));
}

function getPreviousYear(): int
{
    return getActiveYear() - 1;
}

function getNextYear(): int
{
    return getActiveYear() + 1;
}

function getNextMonth(): int|string
{
    return convertNum(((int)getActiveMonth() == 12) ? 1 : ((int)getActiveMonth() + 1));
}

function getPreviousMonth(): int|string
{
    return convertNum(((int)getActiveMonth() == 1) ? 12 : ((int)getActiveMonth() - 1));

}

function getActiveYear(): int
{
    return array_key_exists('year', $_GET) ? (int)$_GET['year'] : (int)date('Y');
}
