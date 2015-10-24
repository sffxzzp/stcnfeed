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
    "db"    =>  $db
);
//check time
$oldTime = mysqli_fetch_array(sqlExec($sqlInfo, "SELECT * FROM ".$tabletime));
$oldTime = intval($oldTime["time"]);
$newTime = time();
//if timediff > 1m, compare old to new, del all old **outdated** data.
if ($newTime-$oldTime>60) {
    //record new timestamp;
    sqlExec($sqlInfo, "truncate table ".$tabletime);
    sqlExec($sqlInfo, "insert into ".$tabletime." (ID, time) values (0, ".$newTime.")");
    /*---------------------
    get new data, will compare to the old one.
    the exact method is:
    1. first get old & new data.
    2. new compares to old, and del all in new that had appeared in old.
    3. calculate how many new still exist, and del equal number of old data in sql.
    //*. the new lefts will be sent to get full page data.
    4. finally insert into database.
    ---------------------*/
    $oldData = getData($sqlInfo, $tablelist);
    $newData = handlePage(curl('http://steamcn.com/forum.php?mod=guide&view=newthread'));
    for ($i=0;$i<count($oldData);$i++) {
        for ($j=0;$j<count($newData);$j++) {
            if ($newData[$j][0]==$oldData[$i][0]) {
                delArray($newData, $j);
                break;
            }
        }
    }
    $newNum = count($newData);
    for ($i=0;$i<$newNum;$i++) {
        sqlExec($sqlInfo, 'delete from list where tid = '.$oldData[$i][0].';');
    }
    for ($i=$newNum-1;$i>=0;$i--) {
        sqlExec($sqlInfo, 'insert into '.$tablelist.' (ID, tid, title, category, auther, description) values (0, '.$newData[$i][0].', "'.$newData[$i][1].'", "'.$newData[$i][2].'", "'.$newData[$i][3].'", "'.$newData[$i][4].'")');
        popen('curl http://'.$_SERVER['SERVER_NAME'].'/page.php?tid='.$newData[$i][0], 'r');
        //curl('http://'.$_SERVER['SERVER_NAME'].'/page.php?tid='.$newData[$i][0]);
    }
}
//get data that saves in database.
$oldData = getData($sqlInfo, $tablelist);
//print data in rss format.
showPage($oldData);
?>