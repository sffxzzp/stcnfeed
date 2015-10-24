<?php
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
if (isset($_GET["tid"])) {
    $pageCont = curl('http://steamcn.com/forum.php?mod=viewthread&tid='.$_GET["tid"]);
    preg_match('/<table.+?>(<tr><td .+?t_f.+?>[\s\S]*?)<\/table/', $pageCont, $postCont);
    $postCont = preg_replace('/<ignore_js_op>[\s\S]*?<\/ignore_js_op>/', '', $postCont[1]);
    $postCont = str_replace('<br />', '', $postCont);
    $postCont = preg_replace('/<i class=\"pstatus\">.*?<\/i>/', '', $postCont);
    $postCont = preg_replace('/style>.+?<\/style>/', '', $postCont);
    $postCont = str_replace('&nbsp;', '', $postCont);
    $postCont = preg_replace('/<a href=\"member\.php.+?>.+?<\/a>/', '|', $postCont);
    $postCont = preg_replace('/<\/.+?>/', '|', $postCont);
    $postCont = preg_replace('/<.+?>/', '', $postCont);
    $postCont = str_replace('|||||', '||||', $postCont);
    $postCont = str_replace('||||', '|||', $postCont);
    $postCont = str_replace('|||', '||', $postCont);
    $postCont = str_replace('||', '|', $postCont);
    $postCont = str_replace('|', '<br />', $postCont);
    $postCont = preg_replace('/\n/', '', $postCont);
    if (strlen($postCont)>0) {
        sqlExec($sqlInfo, 'update '.$tablelist.' set description = "'.urlencode($postCont).'" where tid = '.$_GET["tid"].';');
    }
    else {
        sqlExec($sqlInfo, 'update '.$tablelist.' set description = "Server can\'t fetch page data." where tid = '.$_GET["tid"]);
    }
}
?>