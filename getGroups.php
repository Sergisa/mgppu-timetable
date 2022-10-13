<?php
include 'vendor/autoload.php';
include 'functions.php';
echo getGroups()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
