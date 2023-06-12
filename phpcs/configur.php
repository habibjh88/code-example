<?php 
/*  
How to install and configure phpcs for wordpress in the phpstrom.
==================================================================

Step-1:     composer global require "squizlabs/php_codesniffer=*"              //Install phpcs in you global path
            Github source: https://github.com/squizlabs/PHP_CodeSniffer

Step-2:     Go to the below path when the phpcs has installed and clone wpcs git file - 
                Windows:    C:\Users\{UserName}\AppData\Roaming\Composer\vendor
                MAC:        /Users/{UserName}/.composer/vendor/bin/phpcs
            git clone -b master https://github.com/WordPress/WordPress-Coding-Standards.git wpcs            

Step-3:     Open PHPStrom > Settings > PHP > Quality Tools 
            > PHP_CodeSniffer:  1.  [ON]
                                2.  Click 3 dot [...] and set the below path for windows-
                                    PHP_CodeSniffer: C:\Users\DELL\AppData\Roaming\Composer\vendor\squizlabs\php_codesniffer\bin\phpcs.bat   //should change for mac
                                    PHP to phpcbf: C:\Users\DELL\AppData\Roaming\Composer\vendor\squizlabs\php_codesniffer\bin\phpcbf.bat    //change for mac
                                3.  Coding standard: [Custom] and set phpcs.xml path from your plugin.
                                
*/