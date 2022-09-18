<?php

// pathinfo(string $path, int $flags = PATHINFO_ALL): array|string

$path_parts = pathinfo('/www/htdocs/inc/lib.inc.php');

echo $path_parts['dirname'], "\n"; // Output- /www/htdocs/inc
echo $path_parts['basename'], "\n"; // Output- lib.inc.php
echo $path_parts['extension'], "\n"; // Output- php
echo $path_parts['filename'], "\n"; // Output- lib.inc




