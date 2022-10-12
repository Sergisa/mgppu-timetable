<?php
include 'vendor/autoload.php';
include 'functions.php';
echo getData()->values()->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
//echo json_last_error_msg();
