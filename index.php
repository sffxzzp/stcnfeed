<?php 
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header("Content-type: text/html; charset=utf-8");
require_once('functions.php');
$installpath = '/steam/feed-nodb';

$newData = handlePage(curl('https://steamcn.com/forum.php?mod=guide&view=newthread'));
$newNum = count($newData);
if ($newNum>0) {
    showPage($newData, $installpath);
}
?>