<?php 
/*  
How to install and configure phpcs for wordpress in the phpstrom.
==================================================================

Step-1:     composer global require "squizlabs/php_codesniffer=*"              //Install phpcs in you global path
Github:     https://github.com/squizlabs/PHP_CodeSniffer

Step-2:     Go to this path when the phpcs has installed - 
                Windows:    C:\Users\{UserName}\AppData\Roaming\Composer\vendor
                MAC:        /Users/{UserName}/.composer/vendor/bin/phpcs
            git clone -b master https://github.com/WordPress/WordPress-Coding-Standards.git wpcs            

Step-3:     Open PHPStrom > Settings > PHP > Quality Tools 
            > PHP_CodeSniffer:  1. [ON]
                                2. Click 3 dot [...] and set path for PHP_CodeSniffer: C:\Users\DELL\AppData\Roaming\Composer\vendor\squizlabs\php_codesniffer\bin\phpcs.bat
*/