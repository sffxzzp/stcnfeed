<?php
$db = getenv('MYSQLDATABASE');
$host = getenv('MYSQLHOST').':'.getenv('MYSQLPORT');
$user = getenv('MYSQLUSER');
$pwd = getenv('MYSQLPASSWORD');
//---------------
$prefix = 'stcn_';
$tabletime = $prefix.'time';
$tablelist = $prefix.'list';
//---------------
// the following should be set if placed in subfolder.
// if placed in www root, leave $installpath = '';
// e.g. if placed in subfolder and could open using link like: http://abc.com/steam/feed/
//       you should set $installpath to '/steam/feed'
//---------------
$installpath = '';