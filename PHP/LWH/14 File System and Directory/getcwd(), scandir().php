<?php

// check current directory
$dir = getcwd();  //same as - dirname(__FILE__))
echo $dir;

// Check all directory and file on the current dir:

$all_file_dir = scandir($dir);
var_dump($all_file_dir);

// check is_dir or file 
foreach ($all_file_dir as $entry) {
    if (!in_array($entry, ['.', '..'])) {
        if (is_dir($entry)) {
            echo "[d] {$entry}";
        } else {
            echo "[f] {$entry}";
        }
    }
}

echo "<br>";
echo "<br>";

//Both are same::
var_dump(dirname(__FILE__));
var_dump(getcwd());

//Another way to find file and folder
$entries = opendir(getcwd());

while (false !== ($entry = readdir($entries))) {
    echo $entry . "\n";
}

//TODO: Same thing doing by a Class
class MyDirectory {
    private $dirs = [];
    private $files = [];
    function __constructor($path)
    {
        if (is_readable($path)) {
            $entries = scandir($path);
            foreach ($this->entries as $entry) {
                if (!in_array($entry, ['.', '..'])) {
                    if (is_dir($entry)) {
                        array_push($this->dirs, $entry);
                    } else {
                        array_push($this->files, $entry);
                    }
                }
            }
        }
    }

    public function getDirectories(){
        return $this->dirs;
    }

    public function getFiles(){
        return $this->files;
    }
}

$d = new MyDirectory(__FILE__);
$d->getDirectories();
$d->getFiles();

var_dump("../" . dirname(__FILE__));
