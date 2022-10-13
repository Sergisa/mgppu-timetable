<?php
include 'vendor/autoload.php';
include 'functions.php';
echo getPreparedTimetable()->values()->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
