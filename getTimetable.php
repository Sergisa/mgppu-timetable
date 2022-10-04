<?php
include 'vendor/autoload.php';
include 'functions.php';
echo json_encode(getData()->values()->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
