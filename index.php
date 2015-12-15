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
date_default_timezone_set('Asia/Shanghai');
//check time
$oldTime = mysqli_fetch_array(sqlExec($sqlInfo, "SELECT * FROM ".$tabletime));
$oldTime = intval($oldTime["time"]);
$newTime = time();
//if timediff > 1m, compare old to new, del all old **outdated** data.
if ($newTime-$oldTime>1) {
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
    //considered speed and stable. the `handlePage` function would only return a 10-length array.
    $newData = handlePage(curl('http://steamcn.com/forum.php?mod=guide&view=newthread'));
    for ($i=0;$i<count($oldData);$i++) {
        for ($j=0;$j<count($newData);$j++) {
            if ($newData[$j][0]==$oldData[$i][0]) {
                delArray($newData, $j);
                break;
            }
        }
    }
    for ($i=0;$i<count($oldData);$i++) {
        if (strtotime(urldecode($oldData[$i][5]))<$newTime-86400) {
            sqlExec($sqlInfo, 'delete from list where tid = '.$oldData[$i][0].';');
        }
    }
    $newNum = count($newData);
    if ($newNum>0) {
        for ($i=$newNum-1;$i>=0;$i--) {
            sqlExec($sqlInfo, 'insert into '.$tablelist.' (ID, tid, title, category, auther, description, time) values (0, '.$newData[$i][0].', "'.$newData[$i][1].'", "'.$newData[$i][2].'", "'.$newData[$i][3].'", "'.$newData[$i][4].'", "'.$newData[$i][5].'")');
            //if server is strong enough you could del the //.
            //del // in next line if you are using Linux.
            popen('curl http://'.$_SERVER['SERVER_NAME'].'/page.php?tid='.$newData[$i][0], 'r');
            //del // in next line if you are using Win / not sure what sys you are using.
            //curl('http://'.$_SERVER['SERVER_NAME'].'/page.php?tid='.$newData[$i][0]);
        }
    }
}
//check if init from install.
if (isset($_GET["init"])) {
    echo 'data base init success!';
}
//if not
else {
    //get data that saves in database.
    $oldData = getData($sqlInfo, $tablelist);
    //print data in rss format.
    showPage($oldData);
}
?>