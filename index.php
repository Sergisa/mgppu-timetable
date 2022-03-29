<?php
include 'vendor/autoload.php';
//$file = readfile('Result_152.json');
//$file = readfile('Result_200.json');
$myfile = fopen("Result_152.json", "r") or die("Unable to open file!");
$file = fread($myfile, filesize("Result_152.json"));
fclose($myfile);
$timetable = collect(json_decode($file, true));

/*$timetable = $timetable->mapToGroups(function ($item, $key) {
    return ["BuildingObj" => [
        'building' => $item['Building'],
        'floor' => $item['Floor']
    ]];
});*/
$timetable = $timetable->map(function ($item, $key) use ($timetable) {
    $newObj = collect($item)->prepend([
        'building' => $item['Building'],
        'floor' => $item['Floor']
    ], "BuildingObj")->filter(function ($item, $key) {
        return !in_array($key, ["Floor", "Building"]);
    });;

    //$newObj->toJson();
    return $newObj->toArray();
})
?>
<pre><code><?php
        echo json_encode($timetable->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        ?></code></pre>
