<?php
include 'vendor/autoload.php';
include 'functions.php';
ini_set('memory_limit', '-1');
$myfile = fopen("data/Timetable2022.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("data/Timetable2022.json"));
fclose($myfile);
//$timetable = collect(json_decode($file, true));
//$timetable = groupCollapsing($timetable);
//$currentDate = date("d.m.Y");
//$currentDate = date("d.m.Y", strtotime("+1 day", strtotime(date("d.m.Y"))));
//$timetable = $timetable->pluck("Group")->unique()->values();
//$timetable = collapseSimilarities($timetable)->groupBy('dayDate')->sortBy('TimeStart');
//echo json_encode($timetable->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$mustache = new Mustache_Engine(array(
    //'template_class_prefix' => '__MyTemplates_',
    'cache' => dirname(__FILE__) . '/tmp/cache/mustache',
    'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
    //'cache_lambda_templates' => true,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
    //'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views/partials'),
    /*'helpers' => array('i18n' => function($text) {
        // do something translatey here...
    }),*/
    /*'escape' => function($value) {
        return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
    },*/
    //'charset' => 'ISO-8859-1',
    //'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    //'strict_callables' => true,
    //'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
));

$tpl = $mustache->loadTemplate('day'); // loads __DIR__.'/views/foo.mustache';
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
        .debug {
            color: #FCBB6D;
        }
    </style>
</head>
<body>
<pre class="debug"><code><?php
        echo $tpl->render([
            'bar' => [
                'y' => 'baz'
            ]
        ]);
        ?></code></pre>

</body>
</html>
