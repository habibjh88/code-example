<?php 
//make multiple file and write dynamic data
//File create / File make / make file / made file / file create / file / 
$arr = [
    'A',
    'B',
    'C',
    'd'
];
foreach($arr as $a){
    $myfile = fopen($a.".txt", "w") or die("Unable to open file!");
    fwrite($myfile, $a);
    fclose($myfile);
}
