<?php
include 'vendor/autoload.php';
include 'functions.php';
echo getProfessors()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE);
