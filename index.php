<?php 
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once('functions.php');
if (file_exists('config.php')) {
    require_once('config.php');
    if (!isset($db)&&!isset($host)&&!isset($user)&!isset($pwd)) {
        header("location:install.php");
    }
}
else {
    header("location:install.php");
}
$sqlInfo = array(
    "host"  =>  $host,
    "user"  =>  $user,
    "pwd"   =>  $pwd,
    "db"    =>  $db);
//sqlInit($sqlInfo, $tabletime, $tablelist);
//sqlExec($sqlInfo, $command);
//check time
$oldTime = mysqli_fetch_array(sqlExec($sqlInfo, "SELECT * FROM ".$tabletime));
$oldTime = intval($oldTime["time"]);
$newTime = time();
//if timediff > 1m, del old table get new data.
if ($newTime-$oldTime>60) {
    sqlExec($sqlInfo, "truncate table ".$tablelist);
    sqlExec($sqlInfo, "truncate table ".$tabletime);
    sqlExec($sqlInfo, "insert into ".$tabletime." (ID, time) values (0, ".$newTime.")");
    $newData = handlePage(curl('http://steamcn.com/forum.php?mod=guide&view=newthread'));
    for ($i=0;$i<count($newData);$i++) {
        sqlExec($sqlInfo, 'insert into '.$tablelist.' (ID, tid, title, category, auther) values (0, '.$newData[$i][0].', "'.$newData[$i][1].'", "'.$newData[$i][2].'", "'.$newData[$i][3].'")');
    }
}
//get data that saves in database.
$Data = sqlExec($sqlInfo, "SELECT * FROM ".$tablelist);
$oldData = array();
while ($row = mysqli_fetch_array($Data)) {
    for ($i=0;$i<5;$i++) {delArray($row, $i+1);}
    delArray($row, 0);
    $oldData[] = $row;
}
//print data in rss format.
showPage($oldData);
?>