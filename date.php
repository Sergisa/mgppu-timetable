<?php

function hasNextMonth(): bool
{
    return getActiveMonth() < 12;
}

function hasPreviousMonth(): bool
{
    return getActiveMonth() > 1;
}

function getActiveMonth(): int|string
{
    return array_key_exists('month', $_GET) ? (int)$_GET['month'] : (int)date('m');
}

function getNextMonthLink(): string
{
    return http_build_query(array_merge($_GET, ['month' => getNextMonth()]));
}

function getNextYear(): int
{
    return (int)date('Y') + 1;
}

function getNextMonth(): int|string
{
    if ((getActiveMonth() + 1) >= 10) {
        return (getActiveMonth() == 12) ? '01' : getActiveMonth() + 1;
    } else {
        return '0' . (getActiveMonth() + 1);
    }
}

function getPreviousMonthLink(): string
{
    return http_build_query(array_merge($_GET, ['month' => getPreviousMonth()]));
}

function getPreviousMonth(): int|string
{
    return (getActiveMonth() - 1) >= 10 ? (getActiveMonth() - 1) : '0' . (getActiveMonth() - 1);
}


function getActiveYear(): int
{
    return array_key_exists('year', $_GET) ? (int)$_GET['year'] : (int)date('Y');
}
