<?php
include 'vendor/autoload.php';
include 'functions.php';
ini_set('memory_limit', '-1');
$pdo = new PDO('mysql:dbname=timetable;host=sergisa.ru', 'user15912_sergey', 'isakovs');
$response = $pdo->query('SELECT * FROM timetable')->fetch(PDO::FETCH_OBJ)

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="dist/css/style.css">
    <style>
        :root {
            --bs-body-color: #FCBB6D;
        }
    </style>
</head>
<body class="container">
<?php
echo getLessonType("0x" . strtoupper(bin2hex($response->TypeID)))->full;
?>

</body>
</html>
