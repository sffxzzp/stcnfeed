<?php
$db = 'Database';
$host = 'Database_IP';
$user = 'DB_UserName';
$pwd = 'DB_PassWord';
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
?>