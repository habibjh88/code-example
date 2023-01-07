<?php

//TODO: Make new directory
// mkdir('test');
// mkdir('inc/d1/d2/d3', 0777, true);   //Give permission

// rmdir('inc');
// TODO Remove file by unlink
// unlink('inc/d1/d2/d3/test.txt');


//TODO: wait 5 second - delay 5 second
// sleep(5);
// file_put_contents('inc/d1/d2/d3/test.txt', "hello");

//TODO: Recursively delete file and folder

$path = __DIR__ . DIRECTORY_SEPARATOR . 'inc';
deleteAllDir($path);
// var_dump(scandir($path));
function deleteAllDir($path)
{
    if (!file_exists($path) || ! is_readable($path)) {
        return false;
    }
    $files_in_path = scandir($path);
    //All directory have 2 directory (., ..) own and parent
    if (count($files_in_path) > 2) {
        foreach ($files_in_path as $file) {
            if (!in_array($file, ['.', '..'])) {
                $filePath = $path . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    deleteAllDir($filePath);
                } else {
                    unlink($filePath);
                }
            }
        }
    }
    rmdir($path);
}


//glob() function used as likely as scandir but it give you lot of opportunity for searching file by a pattern
print_r(glob('./upload/*/*/*/*.jpg')); //al jpg file from target folder