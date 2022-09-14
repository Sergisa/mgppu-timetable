<?php
include 'vendor/autoload.php';
include 'functions.php';
ini_set('memory_limit', '-1');
$myfile = fopen("Timetable2022.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("Timetable2022.json"));
fclose($myfile);
$timetable = collect(json_decode($file, true));
$timetable = groupCollapsing($timetable);
$currentDate = date("d.m.Y");
//$currentDate = date("d.m.Y", strtotime("+1 day", strtotime(date("d.m.Y"))));
$timetable = $timetable
    //->where('dayDate', $currentDate)
    ->where("TeacherFIO", "Исаков Сергей Сергеевич")
    ->where("Department.code", "ИТ")
    ->groupBy('dayDate')
    ->sortBy('TimeStart');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="container">
<!--<pre><code>-->
<?php //echo json_encode($timetable, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?><!--</code></pre>-->
<div class="row">
    <div class="col-8 d-flex matrix row justify-content-between align-items-start flex-nowrap overflow-auto" id="monthGrid"></div>
    <div id="listDays" class="col-4">
        <ul class="list-group list-group-flush bg-opacity-100">
            <?php
            foreach ($timetable as $date => $lessons) {
                $lessons = collect($lessons)->map(function ($lesson) use ($lessons) {
                    $similars = $lessons->filter(function ($tmt) use ($lesson) {
                        return $tmt['dayDate'] == $lesson['dayDate']
                            && $tmt['Group']['id'] != $lesson['Group']['id']
                            && $tmt['DisciplineID'] == $lesson['DisciplineID']
                            && $tmt['Number'] == $lesson['Number'];
                    });
                    if ($similars->count() > 0) {
                        $lesson['Group'] = array_merge([$lesson['Group']], $similars->pluck('Group')->unique()->toArray());
                    } else {
                        $lesson['Group'] = [$lesson['Group']];
                    }
                    return $lesson;
                })->unique("Number");
                //echo json_encode($lessons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                echo "<li class='list-group-item'>";
                echo "<div class='labels'>
                    <span class='date me-1'>" . convertDate('d.m', $date) . "</span>";
                foreach (collect($lessons)->pluck("Type") as $type) {
                    echo "<span class='type me-1'>" . mb_substr($type, 0, 1) . "</span>";
                }
                echo "</div>";
                foreach ($lessons as $lesson) {
                    echo "<div>{$lesson['Discipline']} " . implode(' ', collect($lesson['Group'])->pluck('name')->toArray()) . " </div>";
                }
                echo "</li>";
            }
            ?>
        </ul>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const lines = $('.line');
    const lessonAmount = 5;
    const topHeight = 8;

    function generateDay(lessons) {
        return $(`<div class="day"></div>`)
            .append('<div class="lesson">314</div>')
            .append('<div class="lesson">211-212</div>')
            .append('<div class="lesson">211-212</div>')
    }

    function generateGrid() {
        $(`<div class="month"></div>`)
            .append(generateDay())
            .appendTo($('#monthGrid'))
    }

    generateGrid();
</script>
</html>


