<?php
// 1 dot (.) means current directory
// 2 dots (..) means parent directory

$dir1 = new DirectoryIterator('./');
foreach ($dir1 as $fileInfo) {
    if ($fileInfo->isDot()) continue;
    echo $fileInfo->getFilename() .'->'. $fileInfo->getPathname()  . "<br>\n";
}


//TODO: RecursiveDirectoryIterator

$directory = new \RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new \RecursiveIteratorIterator($directory);
$files = array();
$totalSize = 0;
foreach ($iterator as $file) {
  if ($file->isFile()) {
        $totalSize +=$file->getSize();
  }

  echo $file->getPath().DIRECTORY_SEPARATOR.$file->getFilename;
}

echo "Calculate Total folder size {$totalSize} bytes";