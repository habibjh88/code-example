<?php 
/*
Install Xdebug 
------------------------------------------------------------------
You can add Xdebug to Laragon easily.
Method 1: 
Copy phpinfo() content:
http://localhost/?q=info

Paste the copied data to the form in the Xdebug Wizard page
https://xdebug.org/wizard.php

----------------------
1. Download [ php_xdebug-3.0.4-7.2-vc15-x86_64.dll ]   //It will depend on php version
2. Move the downloaded file to [ C:\laragon\bin\php\php-7.2.19-Win32-VC15-x64\ext ]
3. Edit [ php.ini ] and add below line at the end of the file
   i) zend_extension = C:\laragon\bin\php\.....PHP_FOLDER.....\ext\.....php_xdebug_file_name.......
   ii) Example: zend_extension = C:\laragon\bin\php\php-7.2.19-Win32-VC15-x64\ext\php_xdebug-3.0.4-7.2-vc15-x86_64.dll
4. Restart the webserver



Install WP CLI 
-------------------------------------------------

1. Download wp-cli.phar file from ===> https://raw.github.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
2. Move the file to [ C:\wp-cli ] folder
3. Add [ wp.bat ] file in the same directory.
3. Pest these code in wp.bat file
    @ECHO OFF
    php "c:/wp-cli/wp-cli.phar" %*
5. Add [ php ] path
4. after added php path just add [ c:\wp-cli ] to your path
5. Restart your PC


Disable PhpMyadmin error 
=================================
 $cfg['SendErrorReports'] = 'never';
 Put above line inside /etc/phpmyadmin/config.inc.php file

 */